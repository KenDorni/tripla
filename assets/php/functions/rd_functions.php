<?php
require_once(__DIR__ . '/../database/db_functions.php');

function readAccount($filter = []) {
    $dbc = dbConnect();
    $query = "SELECT pk_user, email_address, username FROM User";
    $params = [];
    $types = "";

    if (isset($filter['pk_user'])) {
        $query .= " WHERE pk_user = ?";
        $params[] = $filter['pk_user'];
        $types .= "i";
    } 
    elseif (isset($filter['email_address'])) {
        $query .= " WHERE email_address = ?";
        $params[] = $filter['email_address'];
        $types .= "s";
    }

    $result = queryStatement($dbc, $query, $types, ...$params);

    if (!$result) {
        return ['error' => 'Account read failed: ' . mysqli_error($dbc)];
    }

    return fetchAllFields($result);
}

function readItinerary($filter = []) {
    $dbc = dbConnect();
    $query = "SELECT pk_itinerary, creation_date, fk_user_created FROM Itinerary";
    $params = [];
    $types = "";

    if (isset($filter['pk_itinerary'])) {
        $query .= " WHERE pk_itinerary = ?";
        $params[] = $filter['pk_itinerary'];
        $types .= "i";
    }
    elseif (isset($filter['fk_user_created'])) {
        $query .= " WHERE fk_user_created = ?";
        $params[] = $filter['fk_user_created'];
        $types .= "i";
    }

    $result = queryStatement($dbc, $query, $types, ...$params);

    if (!$result) {
        return ['error' => 'Itinerary read failed: ' . mysqli_error($dbc)];
    }

    return fetchAllFields($result);
}

function readItineraryStop($filter = []) {
    $dbc = dbConnect();
    $query = "SELECT 
                pk_itinerary_stop, 
                fk_itinerary_includes, 
                type, 
                value, 
                booking_ref, 
                link, 
                online_ticket, 
                start, 
                stop 
              FROM Itinerary_Stop";
    $params = [];
    $types = "";

    if (isset($filter['pk_itinerary_stop'])) {
        $query .= " WHERE pk_itinerary_stop = ?";
        $params[] = $filter['pk_itinerary_stop'];
        $types .= "i";
    }
    elseif (isset($filter['fk_itinerary_includes'])) {
        $query .= " WHERE fk_itinerary_includes = ?";
        $params[] = $filter['fk_itinerary_includes'];
        $types .= "i";
    }

    $result = queryStatement($dbc, $query, $types, ...$params);

    if (!$result) {
        return ['error' => 'Itinerary stop read failed: ' . mysqli_error($dbc)];
    }

    $rows = fetchAllFields($result);
    
    // Base64 encode BLOB data
    foreach ($rows as &$row) {
        if (isset($row['online_ticket'])) {
            $row['online_ticket'] = base64_encode($row['online_ticket']);
        }
    }
    
    return $rows;
}

function readItineraryTransit($filter = []) {
    $dbc = dbConnect();
    $query = "SELECT 
                pk_itinerary_transit, 
                fk_itinerary_has_assigned, 
                method, 
                booking_ref, 
                link, 
                online_ticket, 
                start, 
                stop 
              FROM Itinerary_Transit";
    $params = [];
    $types = "";

    if (isset($filter['pk_itinerary_transit'])) {
        $query .= " WHERE pk_itinerary_transit = ?";
        $params[] = $filter['pk_itinerary_transit'];
        $types .= "i";
    }
    elseif (isset($filter['fk_itinerary_has_assigned'])) {
        $query .= " WHERE fk_itinerary_has_assigned = ?";
        $params[] = $filter['fk_itinerary_has_assigned'];
        $types .= "i";
    }
    elseif (isset($filter['method'])) {
        $query .= " WHERE method = ?";
        $params[] = $filter['method'];
        $types .= "s";
    }

    $result = queryStatement($dbc, $query, $types, ...$params);

    if (!$result) {
        return ['error' => 'Transit read failed: ' . mysqli_error($dbc)];
    }

    $rows = fetchAllFields($result);
    
    // Base64 encode BLOB data
    foreach ($rows as &$row) {
        if (isset($row['online_ticket'])) {
            $row['online_ticket'] = base64_encode($row['online_ticket']);
        }
    }
    
    return $rows;
}
?>