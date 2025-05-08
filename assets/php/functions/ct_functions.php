<?php
require_once(__DIR__ . '/../database/db_functions.php');

function createAccount($data) {
    $dbc = dbConnect();
    
    // Validate required fields
    if (!isset($data['email_address']) || !isset($data['password'])) {
        throw new Exception("Email and password are required");
    }
    
    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insert user
    $query = "INSERT INTO User (email_address, password, username) VALUES (?, ?, ?)";
    $result = queryStatement($dbc, $query, 'sss', 
        $data['email_address'], 
        $hashedPassword, 
        $data['username'] ?? null
    );
    
    if (!$result) {
        throw new Exception("Failed to create account");
    }
    
    $userId = mysqli_insert_id($dbc);
    
    // Store the foreign key in session
    setForeignKey('Account', $userId);
    
    // If there's cookie data, transfer it to database
    if (isset($_COOKIE['tripla_data'])) {
        $cookieData = json_decode($_COOKIE['tripla_data'], true);
        transferCookieDataToDatabase($dbc, $userId, $cookieData);
        setcookie('tripla_data', '', time() - 3600, "/"); // Clear cookie
    }
    
    return [
        'success' => true,
        'message' => 'Account created successfully',
        'data' => ['user_id' => $userId]
    ];
}

function createItinerary($data, $userId, $saveToDb) {
    if ($saveToDb) {
        $dbc = dbConnect();
        $query = "INSERT INTO Itinerary (fk_user_created) VALUES (?)";
        $result = queryStatement($dbc, $query, 'i', $userId);
        
        if (!$result) {
            throw new Exception("Failed to create itinerary");
        }
        
        $itineraryId = mysqli_insert_id($dbc);
        
        // Store the foreign key in session
        setForeignKey('Itinerary', $itineraryId);
        
        return ['itinerary_id' => $itineraryId];
    } else {
        return ['itinerary_id' => 'temp_' . uniqid()];
    }
}

function createItineraryStop($data, $userId, $saveToDb) {
    // Validate required fields
    $required = ['fk_itinerary_includes', 'type', 'value', 'start', 'stop'];
    foreach ($required as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    if ($saveToDb) {
        $dbc = dbConnect();
        $query = "INSERT INTO Itinerary_Stop (
            fk_itinerary_includes, type, value, booking_ref, link, online_ticket, start, stop
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $result = queryStatement($dbc, $query, 'issssbss',
            $data['fk_itinerary_includes'],
            $data['type'],
            $data['value'],
            $data['booking_ref'] ?? null,
            $data['link'] ?? null,
            $data['online_ticket'] ?? null,
            $data['start'],
            $data['stop']
        );
        
        if (!$result) {
            throw new Exception("Failed to create itinerary stop");
        }
        
        $stopId = mysqli_insert_id($dbc);
        
        // Store the foreign key in session
        setForeignKey('Itinerary_Stop', $stopId);
        
        return ['stop_id' => $stopId];
    } else {
        return ['stop_id' => 'temp_' . uniqid()];
    }
}

function createItineraryTransit($data, $userId, $saveToDb) {
    // Validate required fields
    $required = ['fk_itinerary_has_assigned', 'method', 'booking_ref', 'link', 'start', 'stop'];
    foreach ($required as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    if ($saveToDb) {
        $dbc = dbConnect();
        $query = "INSERT INTO Itinerary_Transit (
            method, booking_ref, link, online_ticket, start, stop, fk_itinerary_has_assigned
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $result = queryStatement($dbc, $query, 'sssbssi',
            $data['method'],
            $data['booking_ref'],
            $data['link'],
            $data['online_ticket'] ?? null,
            $data['start'],
            $data['stop'],
            $data['fk_itinerary_has_assigned']
        );
        
        if (!$result) {
            throw new Exception("Failed to create itinerary transit");
        }
        
        $transitId = mysqli_insert_id($dbc);
        
        // Store the foreign key in session
        setForeignKey('Itinerary_Transit', $transitId);
        
        return ['transit_id' => $transitId];
    } else {
        return ['transit_id' => 'temp_' . uniqid()];
    }
}

function transferCookieDataToDatabase($dbc, $userId, $cookieData) {
    // Transfer itineraries first
    if (isset($cookieData['Itinerary'])) {
        foreach ($cookieData['Itinerary'] as $itinerary) {
            $itinerary['fk_user_created'] = $userId;
            createItinerary($itinerary, $userId, true);
        }
    }
    
    // Then stops and transits (they depend on itineraries)
    // Note: This is simplified - you'd need to handle the relationships properly
    if (isset($cookieData['Itinerary_Stop'])) {
        foreach ($cookieData['Itinerary_Stop'] as $stop) {
            createItineraryStop($stop, $userId, true);
        }
    }
    
    if (isset($cookieData['Itinerary_Transit'])) {
        foreach ($cookieData['Itinerary_Transit'] as $transit) {
            createItineraryTransit($transit, $userId, true);
        }
    }
}
?>