<?php
require_once __DIR__ . "/../database/db_credentials.php";
require_once __DIR__ . "/../database/db_functions.php";

function createAccount($values)
{
    $dbc = dbConnect();
    if (!$dbc) {
        return ["success" => false, "message" => "Database connection failed"];
    }

    if (!isset($values["email_address"], $values["password"], $values["username"])) {
        return ["success" => false, "message" => "Missing fields for Account creation"];
    }

    $email = $values["email_address"];
    $password = password_hash($values["password"], PASSWORD_DEFAULT);
    $username = $values["username"];

    $result = queryStatement(
        $dbc,
        "INSERT INTO User (email_address, password, username) VALUES (?, ?, ?)",
        "sss",
        $email,
        $password,
        $username
    );

    mysqli_close($dbc);

    if ($result === true) {
        return ["success" => true, "message" => "Account created successfully"];
    } else {
        return ["success" => false, "message" => "Account creation failed"];
    }
}

function createItinerary($values)
{
    $dbc = dbConnect();
    if (!$dbc) {
        return ["success" => false, "message" => "Database connection failed"];
    }

    if (!isset($values["fk_user_created"])) {
        return ["success" => false, "message" => "Missing user reference for itinerary creation"];
    }

    $fk_user_created = $values["fk_user_created"];
    $result = queryStatement(
        $dbc,
        "INSERT INTO Iternary (creation_date, fk_user_created) VALUES (NOW(), ?)",
        "i",
        $fk_user_created
    );

    mysqli_close($dbc);

    if ($result === true) {
        return ["success" => true, "message" => "Itinerary created successfully"];
    } else {
        return ["success" => false, "message" => "Itinerary creation failed"];
    }
}

function createItineraryStop($values)
{
    $dbc = dbConnect();
    if (!$dbc) {
        return ["success" => false, "message" => "Database connection failed"];
    }

    $requiredFields = ["type", "value", "booking_ref", "link", "online_ticket", "start", "stop", "fk_itinerary_includes"];
    foreach ($requiredFields as $field) {
        if (!isset($values[$field])) {
            return ["success" => false, "message" => "Missing field '$field' for itinerary stop creation"];
        }
    }

    $result = queryStatement(
        $dbc,
        "INSERT INTO Iternary_Stop (type, value, booking_ref, link, online_ticket, start, stop, fk_itinerary_includes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        "sssssssi",
        $values["type"],
        $values["value"],
        $values["booking_ref"],
        $values["link"],
        $values["online_ticket"],
        $values["start"],
        $values["stop"],
        $values["fk_itinerary_includes"]
    );

    mysqli_close($dbc);

    if ($result === true) {
        return ["success" => true, "message" => "Itinerary stop created successfully"];
    } else {
        return ["success" => false, "message" => "Itinerary stop creation failed"];
    }
}

function createItineraryTransit($values)
{
    $dbc = dbConnect();
    if (!$dbc) {
        return ["success" => false, "message" => "Database connection failed"];
    }

    $requiredFields = ["method", "booking_ref", "link", "online_ticket", "start", "stop", "fk_before", "fk_after", "fk_itinerary_has_assigned"];
    foreach ($requiredFields as $field) {
        if (!isset($values[$field])) {
            return ["success" => false, "message" => "Missing field '$field' for itinerary transit creation"];
        }
    }

    $result = queryStatement(
        $dbc,
        "INSERT INTO Iternary_Transit (method, booking_ref, link, online_ticket, start, stop, fk_before, fk_after, fk_itinerary_has_assigned) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "ssssssiii",
        $values["method"],
        $values["booking_ref"],
        $values["link"],
        $values["online_ticket"],
        $values["start"],
        $values["stop"],
        $values["fk_before"],
        $values["fk_after"],
        $values["fk_itinerary_has_assigned"]
    );

    mysqli_close($dbc);

    if ($result === true) {
        return ["success" => true, "message" => "Itinerary transit created successfully"];
    } else {
        return ["success" => false, "message" => "Itinerary transit creation failed"];
    }
}
?>