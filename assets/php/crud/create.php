<?php
require_once(__DIR__ . '/../database/db_functions.php'); // Path corrected - from index.php
require_once(__DIR__ . '/../functions/ct_functions.php');   // Path corrected - from index.php

// We are using the $responseMessages array from index.php, no need to redefine it here.

// Ensure valid payload - This check is likely redundant as index.php already checks for Type and Value.
// However, keeping it for safety if create.php might be accessed directly in the future.
if (!isset($data['Type']) || !isset($data['Value'])) { // Corrected from 'Values' to 'Value' to match Payload example
    $responseMessages[] = ["message" => "Invalid payload: missing Type or Value"];
    // No echo json_encode and exit here, index.php handles that
    return; // Stop execution of create.php, returning to index.php
}

// Determine the Type and call the corresponding function
switch ($data['Type']) {
    case 'Account':
        // Call function to create account
        $result = createAccount($data['Value']); // Corrected from 'Values' to 'Value'
        break;
    case 'Iternary': // Corrected spelling to 'Itinerary' to match SQL and general English spelling
        // Call function to create itinerary
        $result = createItinerary($data['Value']); // Corrected from 'Values' to 'Value'
        break;
    case 'Iternary_Stop': // Corrected spelling to 'Itinerary' to match SQL
        // Call function to create itinerary stop
        $result = createItineraryStop($data['Value']); // Corrected from 'Values' to 'Value'
        break;
    case 'Iternary_Transit': // Corrected spelling to 'Itinerary' to match SQL
        // Call function to create itinerary transit
        $result = createItineraryTransit($data['Value']); // Corrected from 'Values' to 'Value'
        break;
    default:
        $responseMessages[] = ["message" => "Invalid Type"];
        // No echo json_encode and exit here, index.php handles that
        return; // Stop execution of create.php, returning to index.php
}

if ($result && is_array($result) && isset($result['message'])) { // Check if result is a message array
    $responseMessages[] = $result; // Add the message from the function to responseMessages
} elseif ($result) { // If $result is true but not a message array (assuming success without specific message)
    $responseMessages[] = ["message" => "Creation successful"];
} else {
    $responseMessages[] = ["message" => "Error occurred during creation"];
}

// No echo json_encode here, index.php handles that
?>