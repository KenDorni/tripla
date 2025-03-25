<?php
require_once(__DIR__ . '/../database/db_functions.php');

function updateAccount($data) {
    $dbc = dbConnect();
    
    // Pflichtfelder prüfen
    if (!isset($data['pk_user'])) {
        return ['error' => 'Primary key (pk_user) is required'];
    }

    $query = "UPDATE User SET ";
    $updates = [];
    $params = [];
    $types = "";

    // Optionale Felder
    if (isset($data['email_address'])) {
        $updates[] = "email_address = ?";
        $params[] = $data['email_address'];
        $types .= "s";
    }
    if (isset($data['password'])) {
        $updates[] = "password = ?";
        $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        $types .= "s";
    }
    if (isset($data['username'])) {
        $updates[] = "username = ?";
        $params[] = $data['username'];
        $types .= "s";
    }

    if (empty($updates)) {
        return ['error' => 'No fields to update'];
    }

    $query .= implode(", ", $updates) . " WHERE pk_user = ?";
    $params[] = $data['pk_user'];
    $types .= "i";

    $result = queryStatement($dbc, $query, $types, ...$params);
    
    return $result ? mysqli_affected_rows($dbc) : ['error' => mysqli_error($dbc)];
}

function updateItinerary($data) {
    $dbc = dbConnect();
    
    if (!isset($data['pk_itinerary'])) {
        return ['error' => 'Primary key (pk_itinerary) is required'];
    }

    // Itinerary hat nur ein aktualisierbares Feld (fk_user_created)
    if (!isset($data['fk_user_created'])) {
        return ['error' => 'No fields to update'];
    }

    $query = "UPDATE Itinerary SET fk_user_created = ? WHERE pk_itinerary = ?";
    $result = queryStatement($dbc, $query, 'ii', $data['fk_user_created'], $data['pk_itinerary']);
    
    return $result ? mysqli_affected_rows($dbc) : ['error' => mysqli_error($dbc)];
}

function updateItineraryStop($data) {
    $dbc = dbConnect();
    
    if (!isset($data['pk_itinerary_stop'])) {
        return ['error' => 'Primary key (pk_itinerary_stop) is required'];
    }

    $query = "UPDATE Itinerary_Stop SET ";
    $updates = [];
    $params = [];
    $types = "";

    // Alle aktualisierbaren Felder
    $fields = [
        'type' => 's',
        'value' => 's',
        'booking_ref' => 's',
        'link' => 's',
        'online_ticket' => 'b',
        'start' => 's',
        'stop' => 's'
    ];

    foreach ($fields as $field => $type) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $val = ($field === 'online_ticket') ? base64_decode($data[$field]) : $data[$field];
            $params[] = $val;
            $types .= $type;
        }
    }

    if (empty($updates)) {
        return ['error' => 'No fields to update'];
    }

    $query .= implode(", ", $updates) . " WHERE pk_itinerary_stop = ?";
    $params[] = $data['pk_itinerary_stop'];
    $types .= "i";

    $result = queryStatement($dbc, $query, $types, ...$params);
    
    return $result ? mysqli_affected_rows($dbc) : ['error' => mysqli_error($dbc)];
}

function updateItineraryTransit($data) {
    $dbc = dbConnect();
    
    if (!isset($data['pk_itinerary_transit'])) {
        return ['error' => 'Primary key (pk_itinerary_transit) is required'];
    }

    $query = "UPDATE Itinerary_Transit SET ";
    $updates = [];
    $params = [];
    $types = "";

    // Alle aktualisierbaren Felder
    $fields = [
        'method' => 's',
        'booking_ref' => 's',
        'link' => 's',
        'online_ticket' => 's', // Achtung: In Ihrer SQL ist dies VARCHAR(12)
        'start' => 's',
        'stop' => 's'
    ];

    foreach ($fields as $field => $type) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $params[] = $data[$field];
            $types .= $type;
        }
    }

    if (empty($updates)) {
        return ['error' => 'No fields to update'];
    }

    $query .= implode(", ", $updates) . " WHERE pk_itinerary_transit = ?";
    $params[] = $data['pk_itinerary_transit'];
    $types .= "i";

    $result = queryStatement($dbc, $query, $types, ...$params);
    
    return $result ? mysqli_affected_rows($dbc) : ['error' => mysqli_error($dbc)];
}

?>