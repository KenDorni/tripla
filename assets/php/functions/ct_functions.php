<?php
require_once(__DIR__ . '/../database/db_functions.php'); // Path corrected - from index.php

/**
 * Create a new account
 * @param array $data The user data (e.g., username, email, password)
 * @return array Returns an array with a message about the account creation status.
 */
function createAccount($data) {
    $dbc = dbConnect();

    $email = $data['email_address'];
    $password = $data['password']; // Make sure you hash the password securely
    $username = $data['username'];

    $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Use password_hash to securely store the password

    // SQL query to insert a new user into the 'User' table
    $query = "INSERT INTO User (email_address, password, username) VALUES (?, ?, ?)";
    $result = queryStatement($dbc, $query, 'sss', $email, $passwordHash, $username);

    if ($result) {
        return ['message' => 'Account successfully created'];
    } else {
        return ['message' => 'Error occurred during account creation', 'db_error' => mysqli_error($dbc)]; // Include db error for debugging
    }
}

/**
 * Create a new itinerary
 * @param array $data The itinerary data (e.g., user ID, name, etc.)
 * @return array Returns an array with a message about the itinerary creation status.
 */
function createItinerary($data)
{
    $dbc = dbConnect();
    // Prepare the query to insert the new itinerary into the Iternary table (Corrected table name)
    $query = "INSERT INTO Iternary (fk_user_created, creation_date) VALUES (?, NOW())"; // Assuming you only need user ID and creation date, and creation_date is auto-set
    // Call the queryStatement function to execute the query
    $result = queryStatement($dbc, $query, "i", $data['fk_user_created']); // Corrected parameter type to integer, assuming fk_user_created is an integer

    if ($result) {
        return ['message' => 'Itinerary successfully created'];
    } else {
        return ['message' => 'Error occurred during itinerary creation', 'db_error' => mysqli_error($dbc)]; // Include db error for debugging
    }
}

/**
 * Create a new itinerary stop
 * @param array $data The itinerary stop data
 * @return array Returns an array with a message about the itinerary stop creation status.
 */
function createItineraryStop($data)
{
    $dbc = dbConnect();
    // Ensure valid 'online_ticket' is passed as base64-encoded
    $online_ticket = base64_decode($data['online_ticket']); // Convert base64 string to binary data

    // Prepare the query to insert the new stop into the Iternary_Stop table (Corrected table name and column names to match schema)
    $query = "INSERT INTO Iternary_Stop (fk_itinerary_includes, type, value, booking_ref, link, online_ticket, start, stop)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Call the queryStatement function to execute the query
    $result = queryStatement(dbConnect(), $query, "issssbss", // Correct type string - this one was correct before
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
        return ['message' => 'Error occurred during itinerary stop creation', 'db_error' => mysqli_error($dbc)]; // Include db error for debugging
    }
}

/**
 * Create a new itinerary transit
 * @param array $data The transit data
 * @return array Returns an array with a message about the itinerary transit creation status.
 */
function createItineraryTransit($data)
{
    $dbc = dbConnect();
    // Prepare the query to insert the new transit into the Iternary_Transit table (Corrected table name and column names)
    $query = "INSERT INTO Iternary_Transit (fk_itinerary_has_assigned, fk_before, fk_after, method, booking_ref, link, online_ticket, start, stop)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Call the queryStatement function to execute the query
    $result = queryStatement(dbConnect(), $query, "iiiisssbs", // **Corrected type string to "iiiisssbs"** - removed one 's' from the end
        $data['fk_itinerary_has_assigned'],
        $data['fk_before'],
        $data['fk_after'],
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
        return ['message' => 'Error occurred during itinerary transit creation', 'db_error' => mysqli_error($dbc)]; // Include db error for debugging
    }
}
?>