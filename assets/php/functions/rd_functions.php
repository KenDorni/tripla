<?php
require_once(__DIR__ . '/../database/db_functions.php');

// Get All Itineraries for a User
function getAllItineraries($user_id)
{
    $dbc = dbConnect();
    $query = "SELECT * FROM Itinerary WHERE fk_user_created = ?";
    $result = queryStatement($dbc, $query, "i", $user_id);

    if ($result) {
        $itineraries = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return ['message' => 'Itineraries retrieved successfully', 'data' => $itineraries];
    } else {
        return ['message' => 'Error occurred while retrieving itineraries', 'db_error' => mysqli_error($dbc)];
    }
}

// Get Itinerary by ID
function getItineraryById($itinerary_id)
{
    $dbc = dbConnect();
    $query = "SELECT * FROM Itinerary WHERE pk_itinerary = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "i", $itinerary_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $itinerary = mysqli_fetch_assoc($result);
        return ['message' => 'Itinerary retrieved successfully', 'data' => $itinerary];
    } else {
        return ['message' => 'Itinerary not found', 'db_error' => mysqli_error($dbc)];
    }
}

// Get All Itinerary Stops
function getAllItineraryStops($itinerary_id)
{
    $dbc = dbConnect();
    $query = "SELECT * FROM Itinerary_Stop WHERE fk_itinerary_includes = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "i", $itinerary_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $stops = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return ['message' => 'Itinerary Stops retrieved successfully', 'data' => $stops];
    } else {
        return ['message' => 'Itinerary Stops not found', 'db_error' => mysqli_error($dbc)];
    }
}

// Get Itinerary Transit by ID
function getItineraryTransitById($transit_id)
{
    $dbc = dbConnect();
    $query = "SELECT * FROM Itinerary_Transit WHERE pk_itinerary_transit = ?";
    $stmt = mysqli_prepare($dbc, $query);
    
    // Debug: Print the ID being passed
    echo "Transit ID: " . $transit_id;  // Debugging line

    mysqli_stmt_bind_param($stmt, "i", $transit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $transit = mysqli_fetch_assoc($result);
        return ['message' => 'Itinerary Transit retrieved successfully', 'data' => $transit];
    } else {
        return ['message' => 'Itinerary Transit not found', 'db_error' => mysqli_error($dbc)];
    }
}

?>
