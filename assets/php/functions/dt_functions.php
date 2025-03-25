<?php
// php/functions/dt_functions.php
require_once(__DIR__ . '/../database/db_functions.php');

function deleteAccount($data) {
    $dbc = dbConnect();
    if (!isset($data['pk_user'])) {
        return ['message' => 'pk_user is required for delete'];
    }

    $query = "DELETE FROM User WHERE pk_user = ?";
    $result = queryStatement($dbc, $query, "i", $data['pk_user']);

    if ($result) {
        return ['message' => 'Account successfully deleted'];
    } else {
        return ['message' => 'Error deleting account', 'db_error' => mysqli_error($dbc)];
    }
}

function deleteItinerary($data) {
    $dbc = dbConnect();
    if (!isset($data['pk_itinerary'])) {
        return ['message' => 'pk_itinerary is required for delete'];
    }

    $query = "DELETE FROM Itinerary WHERE pk_itinerary = ?";
    $result = queryStatement($dbc, $query, "i", $data['pk_itinerary']);

    if ($result) {
        return ['message' => 'Itinerary successfully deleted'];
    } else {
        return ['message' => 'Error deleting itinerary', 'db_error' => mysqli_error($dbc)];
    }
}

function deleteItineraryStop($data) {
    $dbc = dbConnect();
    if (!isset($data['pk_itinerary_stop'])) {
        return ['message' => 'pk_itinerary_stop is required for delete'];
    }

    $query = "DELETE FROM Itinerary_Stop WHERE pk_itinerary_stop = ?";
    $result = queryStatement($dbc, $query, "i", $data['pk_itinerary_stop']);

    if ($result) {
        return ['message' => 'Itinerary Stop successfully deleted'];
    } else {
        return ['message' => 'Error deleting itinerary stop', 'db_error' => mysqli_error($dbc)];
    }
}

function deleteItineraryTransit($data) {
    $dbc = dbConnect();
    if (!isset($data['pk_itinerary_transit'])) {
        return ['message' => 'pk_itinerary_transit is required for delete'];
    }

    $query = "DELETE FROM Itinerary_Transit WHERE pk_itinerary_transit = ?";
    $result = queryStatement($dbc, $query, "i", $data['pk_itinerary_transit']);

    if ($result) {
        return ['message' => 'Itinerary Transit successfully deleted'];
    } else {
        return ['message' => 'Error deleting itinerary transit', 'db_error' => mysqli_error($dbc)];
    }
}
?>