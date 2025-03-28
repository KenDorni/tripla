<?php
require_once(__DIR__ . '/../database/db_functions.php');

function deleteAccount($data, $userId) {
    $dbc = dbConnect();
    
    // Security: Users can only delete their own account
    if (!isset($data['pk_user']) || $data['pk_user'] != $userId) {
        throw new Exception("Unauthorized account deletion");
    }

    $query = "DELETE FROM User WHERE pk_user = ?";
    $result = queryStatement($dbc, $query, 'i', $userId);
    
    if (!$result) {
        throw new Exception("Account deletion failed");
    }
    
    // Clear session if deleting current account
    if ($userId == $_SESSION['user_id']) {
        session_destroy();
    }
    
    return ['affected_rows' => mysqli_affected_rows($dbc)];
}

function deleteItinerary($data, $userId, $inDatabase) {
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
        
        // ON DELETE CASCADE will handle related stops/transits
        $query = "DELETE FROM Itinerary WHERE pk_itinerary = ?";
        $result = queryStatement($dbc, $query, 'i', $data['pk_itinerary']);
        
        if (!$result) {
            throw new Exception("Itinerary deletion failed");
        }
        
        return ['affected_rows' => mysqli_affected_rows($dbc)];
    }
    
    // For cookie deletions, handled in delete.php
    return ['affected_rows' => 0];
}

function deleteItineraryStop($data, $userId, $inDatabase) {
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
            throw new Exception("Unauthorized to delete this stop");
        }
        
        $query = "DELETE FROM Itinerary_Stop WHERE pk_Itinerary_Stop = ?";
        $result = queryStatement($dbc, $query, 'i', $data['pk_Itinerary_Stop']);
        
        if (!$result) {
            throw new Exception("Stop deletion failed");
        }
        
        return ['affected_rows' => mysqli_affected_rows($dbc)];
    }
    
    // For cookie deletions, handled in delete.php
    return ['affected_rows' => 0];
}

function deleteItineraryTransit($data, $userId, $inDatabase) {
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
            throw new Exception("Unauthorized to delete this transit");
        }
        
        $query = "DELETE FROM Itinerary_Transit WHERE pk_itinerary_transit = ?";
        $result = queryStatement($dbc, $query, 'i', $data['pk_itinerary_transit']);
        
        if (!$result) {
            throw new Exception("Transit deletion failed");
        }
        
        return ['affected_rows' => mysqli_affected_rows($dbc)];
    }
    
    // For cookie deletions, handled in delete.php
    return ['affected_rows' => 0];
}
?>