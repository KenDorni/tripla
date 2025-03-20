<?php
require_once __DIR__ . "/../functions/ct_functions.php";

if (!isset($data["Type"]) || !isset($data["Value"])) {
    $responseMessages[] = ["message" => "Invalid payload: missing Type or Value"];
    return;
}

switch ($data["Type"]) {
    case "Account":
        $responseMessages[] = createAccount($data["Value"]);
        break;

    case "Itinerary":
        $responseMessages[] = createItinerary($data["Value"]);
        break;

    case "Stop":
        $responseMessages[] = createItineraryStop($data["Value"]);
        break;

    case "Transit":
        $responseMessages[] = createItineraryTransit($data["Value"]);
        break;

    default:
        $responseMessages[] = ["message" => "Unknown Type: " . $data["Type"]];
        break;
}
?>