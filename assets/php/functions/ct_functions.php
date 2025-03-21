<?php
require_once(__DIR__ . '/../database/db_functions.php');

function createAccount($data) {
    $dbc = dbConnect();
    $email = $data['email_address'];
    $password = $data['password'];
    $username = $data['username'];
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO User (email_address, password, username) VALUES (?, ?, ?)";
    $result = queryStatement($dbc, $query, 'sss', $email, $passwordHash, $username);

    if ($result) {
        return ['message' => 'Account successfully created'];
    } else {
        return ['message' => 'Error occurred during account creation', 'db_error' => mysqli_error($dbc)];
    }
}

function createItinerary($data)
{
    $dbc = dbConnect();
    $query = "INSERT INTO Itinerary (fk_user_created, creation_date) VALUES (?, NOW())";
    $result = queryStatement($dbc, $query, "i", $data['fk_user_created']);

    if ($result) {
        return ['message' => 'Itinerary successfully created'];
    } else {
        return ['message' => 'Error occurred during itinerary creation', 'db_error' => mysqli_error($dbc)];
    }
}

function createItineraryStop($data)
{
    $dbc = dbConnect();
    $online_ticket = base64_decode($data['online_ticket']);
    $query = "INSERT INTO Itinerary_Stop (fk_itinerary_includes, type, value, booking_ref, link, online_ticket, start, stop)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $result = queryStatement($dbc, $query, "issssbss",
        $data['fk_itinerary_includes'],
        $data['type'],
        $data['value'],
        $data['booking_ref'],
        $data['link'],
        $online_ticket,
        $data['start'],
        $data['stop']
    );

    if ($result) {
        return ['message' => 'Itinerary Stop successfully created'];
    } else {
        return ['message' => 'Error occurred during itinerary stop creation', 'db_error' => mysqli_error($dbc)];
    }
}

function createItineraryTransit($data)
{
    $dbc = dbConnect();

    // Only check if fk_itinerary_has_assigned exists
    $queryHasAssigned = "SELECT * FROM Itinerary WHERE pk_itinerary = ?";
    $stmtHasAssigned = mysqli_prepare($dbc, $queryHasAssigned);
    mysqli_stmt_bind_param($stmtHasAssigned, "i", $data['fk_itinerary_has_assigned']);
    mysqli_stmt_execute($stmtHasAssigned);
    $resultHasAssigned = mysqli_stmt_get_result($stmtHasAssigned);
    
    if (mysqli_num_rows($resultHasAssigned) == 0) {
        return ['message' => 'Error: fk_itinerary_has_assigned does not exist in Itinerary table'];
    }

    // Proceed to insert the transit since the fk_itinerary_has_assigned is valid
    $query = "INSERT INTO Itinerary_Transit (fk_itinerary_has_assigned, method, booking_ref, link, online_ticket, start, stop)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $result = queryStatement($dbc, $query, "issssbs",
        $data['fk_itinerary_has_assigned'],
        $data['method'],
        $data['booking_ref'],
        $data['link'],
        base64_decode($data['online_ticket']),
        $data['start'],
        $data['stop']
    );

    if ($result) {
        return ['message' => 'Itinerary Transit successfully created'];
    } else {
        return ['message' => 'Error occurred during itinerary transit creation', 'db_error' => mysqli_error($dbc)];
    }
}

?>
