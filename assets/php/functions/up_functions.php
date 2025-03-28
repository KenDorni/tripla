<?php
require_once(__DIR__ . '/../database/db_functions.php');

function updateAccount($data, $userId) {
    $dbc = dbConnect();
    
    // Security: Users can only update their own account
    if (!isset($data['pk_user']) || $data['pk_user'] != $userId) {
        throw new Exception("Unauthorized account update");
    }

    $updates = [];
    $params = [];
    $types = '';
    
    // Build dynamic update query
    if (isset($data['username'])) {
        $updates[] = "username = ?";
        $params[] = $data['username'];
        $types .= 's';
    }
    
    if (isset($data['password'])) {
        $updates[] = "password = ?";
        $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        $types .= 's';
    }
    
    if (empty($updates)) {
        throw new Exception("No valid fields to update");
    }
    
    $query = "UPDATE User SET " . implode(', ', $updates) . " WHERE pk_user = ?";
    $params[] = $userId;
    $types .= 'i';
    
    $result = queryStatement($dbc, $query, $types, ...$params);
    
    if (!$result) {
        throw new Exception("Account update failed");
    }
    
    return ['affected_rows' => mysqli_affected_rows($dbc)];
}

function updateItinerary($data, $userId, $inDatabase) {
    if ($inDatabase) {
        $dbc = dbConnect();
        
        if (!isset($data['pk_itinerary'])) {
            throw new Exception("Itinerary ID required");
        }
        
        // Verify ownership
        $checkQuery = "SELECT 1 FROM Itinerary WHERE pk_itinerary = ? AND fk_user_created = ?";
        $check = queryStatement($dbc, $checkQuery, 'ii', $data['pk_itinerary'], $userId);
        
        if (!$check || mysqli_num_rows($check) === 0) {
            throw new Exception("Itinerary not found or unauthorized");
        }
        
        // In your current schema, Itinerary only has creation_date which shouldn't be updated
        // Add any future updatable fields here
        throw new Exception("No updatable fields in Itinerary");
    }
    
    // For cookie updates, handled in update.php
    return ['affected_rows' => 0];
}

function updateItineraryStop($data, $userId, $inDatabase) {
    if ($inDatabase) {
        $dbc = dbConnect();
        
        if (!isset($data['pk_Itinerary_Stop'])) {
            throw new Exception("Stop ID required");
        }
        
        // First get the itinerary ID to verify ownership
        $stopQuery = "SELECT fk_itinerary_includes FROM Itinerary_Stop WHERE pk_Itinerary_Stop = ?";
        $stopResult = queryStatement($dbc, $stopQuery, 'i', $data['pk_Itinerary_Stop']);
        
        if (!$stopResult || mysqli_num_rows($stopResult) === 0) {
            throw new Exception("Stop not found");
        }
        
        $stopData = fetchAllFields($stopResult)[0];
        $itineraryId = $stopData['fk_itinerary_includes'];
        
        // Verify itinerary ownership
        $checkQuery = "SELECT 1 FROM Itinerary WHERE pk_itinerary = ? AND fk_user_created = ?";
        $check = queryStatement($dbc, $checkQuery, 'ii', $itineraryId, $userId);
        
        if (!$check || mysqli_num_rows($check) === 0) {
            throw new Exception("Unauthorized to update this stop");
        }
        
        // Build dynamic update query
        $updates = [];
        $params = [];
        $types = '';
        
        $updatableFields = [
            'type' => 's',
            'value' => 's',
            'booking_ref' => 's',
            'link' => 's',
            'online_ticket' => 'b',
            'start' => 's',
            'stop' => 's'
        ];
        
        foreach ($updatableFields as $field => $type) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $data[$field];
                $types .= $type;
            }
        }
        
        if (empty($updates)) {
            throw new Exception("No valid fields to update");
        }
        
        $query = "UPDATE Itinerary_Stop SET " . implode(', ', $updates) . " WHERE pk_Itinerary_Stop = ?";
        $params[] = $data['pk_Itinerary_Stop'];
        $types .= 'i';
        
        $result = queryStatement($dbc, $query, $types, ...$params);
        
        if (!$result) {
            throw new Exception("Stop update failed");
        }
        
        return ['affected_rows' => mysqli_affected_rows($dbc)];
    }
    
    // For cookie updates, handled in update.php
    return ['affected_rows' => 0];
}

function updateItineraryTransit($data, $userId, $inDatabase) {
    if ($inDatabase) {
        $dbc = dbConnect();
        
        if (!isset($data['pk_itinerary_transit'])) {
            throw new Exception("Transit ID required");
        }
        
        // First get the itinerary ID to verify ownership
        $transitQuery = "SELECT fk_itinerary_has_assigned FROM Itinerary_Transit WHERE pk_itinerary_transit = ?";
        $transitResult = queryStatement($dbc, $transitQuery, 'i', $data['pk_itinerary_transit']);
        
        if (!$transitResult || mysqli_num_rows($transitResult) === 0) {
            throw new Exception("Transit not found");
        }
        
        $transitData = fetchAllFields($transitResult)[0];
        $itineraryId = $transitData['fk_itinerary_has_assigned'];
        
        // Verify itinerary ownership
        $checkQuery = "SELECT 1 FROM Itinerary WHERE pk_itinerary = ? AND fk_user_created = ?";
        $check = queryStatement($dbc, $checkQuery, 'ii', $itineraryId, $userId);
        
        if (!$check || mysqli_num_rows($check) === 0) {
            throw new Exception("Unauthorized to update this transit");
        }
        
        // Build dynamic update query
        $updates = [];
        $params = [];
        $types = '';
        
        $updatableFields = [
            'method' => 's',
            'booking_ref' => 's',
            'link' => 's',
            'online_ticket' => 'b',
            'start' => 's',
            'stop' => 's'
        ];
        
        foreach ($updatableFields as $field => $type) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $data[$field];
                $types .= $type;
            }
        }
        
        if (empty($updates)) {
            throw new Exception("No valid fields to update");
        }
        
        $query = "UPDATE Itinerary_Transit SET " . implode(', ', $updates) . " WHERE pk_itinerary_transit = ?";
        $params[] = $data['pk_itinerary_transit'];
        $types .= 'i';
        
        $result = queryStatement($dbc, $query, $types, ...$params);
        
        if (!$result) {
            throw new Exception("Transit update failed");
        }
        
        return ['affected_rows' => mysqli_affected_rows($dbc)];
    }
    
    // For cookie updates, handled in update.php
    return ['affected_rows' => 0];
}
?>