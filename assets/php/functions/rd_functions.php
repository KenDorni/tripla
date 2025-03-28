<?php
require_once(__DIR__ . '/../database/db_functions.php');

function getAccount($filter, $userId) {
    $dbc = dbConnect();
    
    // For security, only allow users to read their own account
    if (!isset($filter['pk_user'])) {
        throw new Exception("User ID required");
    }
    
    if ($filter['pk_user'] != $userId) {
        throw new Exception("Unauthorized access");
    }

    $query = "SELECT pk_user, email_address, username FROM User WHERE pk_user = ?";
    $result = queryStatement($dbc, $query, 'i', $filter['pk_user']);
    
    if (!$result) {
        throw new Exception("Account not found");
    }
    
    $account = fetchAllFields($result);
    return $account[0] ?? null;
}

function getItinerary($filter, $userId, $fromDatabase) {
    if ($fromDatabase) {
        $dbc = dbConnect();
        $query = "SELECT * FROM Itinerary WHERE fk_user_created = ?";
        $params = [$userId];
        $types = 'i';
        
        // Add additional filters if provided
        if (isset($filter['pk_itinerary'])) {
            $query .= " AND pk_itinerary = ?";
            $params[] = $filter['pk_itinerary'];
            $types .= 'i';
        }
        
        $result = queryStatement($dbc, $query, $types, ...$params);
        
        if (!$result) {
            throw new Exception("No itineraries found");
        }
        
        return fetchAllFields($result);
    }
    
    // Return empty array if not from DB (cookie data will be merged in read.php)
    return [];
}

function getItineraryStop($filter, $userId, $fromDatabase) {
    if ($fromDatabase) {
        $dbc = dbConnect();
        
        // First verify the itinerary belongs to the user
        if (isset($filter['fk_itinerary_includes'])) {
            $query = "SELECT 1 FROM Itinerary 
                     WHERE pk_itinerary = ? AND fk_user_created = ?";
            $check = queryStatement($dbc, $query, 'ii', 
                $filter['fk_itinerary_includes'], $userId);
            
            if (!$check || mysqli_num_rows($check) === 0) {
                throw new Exception("Unauthorized access to itinerary");
            }
        }
        
        $query = "SELECT * FROM Itinerary_Stop WHERE 1=1";
        $params = [];
        $types = '';
        
        // Build query dynamically based on filters
        foreach ($filter as $field => $value) {
            if (in_array($field, ['pk_Itinerary_Stop', 'fk_itinerary_includes', 'type'])) {
                $query .= " AND $field = ?";
                $params[] = $value;
                $types .= $field === 'pk_Itinerary_Stop' ? 'i' : (is_int($value) ? 'i' : 's');
            }
        }
        
        $result = queryStatement($dbc, $query, $types, ...$params);
        
        if (!$result) {
            throw new Exception("No stops found");
        }
        
        return fetchAllFields($result);
    }
    
    return [];
}

function getItineraryTransit($filter, $userId, $fromDatabase) {
    if ($fromDatabase) {
        $dbc = dbConnect();
        
        // Verify itinerary ownership first
        if (isset($filter['fk_itinerary_has_assigned'])) {
            $query = "SELECT 1 FROM Itinerary 
                     WHERE pk_itinerary = ? AND fk_user_created = ?";
            $check = queryStatement($dbc, $query, 'ii', 
                $filter['fk_itinerary_has_assigned'], $userId);
            
            if (!$check || mysqli_num_rows($check) === 0) {
                throw new Exception("Unauthorized access to itinerary");
            }
        }
        
        $query = "SELECT * FROM Itinerary_Transit WHERE 1=1";
        $params = [];
        $types = '';
        
        foreach ($filter as $field => $value) {
            if (in_array($field, ['pk_itinerary_transit', 'fk_itinerary_has_assigned', 'method'])) {
                $query .= " AND $field = ?";
                $params[] = $value;
                $types .= is_int($value) ? 'i' : 's';
            }
        }
        
        $result = queryStatement($dbc, $query, $types, ...$params);
        
        if (!$result) {
            throw new Exception("No transit records found");
        }
        
        return fetchAllFields($result);
    }
    
    return [];
}
?>