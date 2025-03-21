<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/ct_functions.php');

if (!isset($data['Type']) || !isset($data['Value'])) {
    $responseMessages[] = ["message" => "Invalid payload: missing Type or Value"];
    echo json_encode($responseMessages);
    exit;
}

switch ($data['Type']) {
    case 'Account':
        $result = createAccount($data['Value']);
        //  {
        //      "Type": "Account",
        //      "Value": {
        //          "email_address": "user@example.com",
        //          "password": "password123",
        //          "username": "username1"
        //      }
        //  }

        break;
    case 'Itinerary':  // Changed from Iternary to Itinerary
        $result = createItinerary($data['Value']);
        //  {
        //      "Type": "Itinerary",
        //      "Value": {
        //          "fk_user_created": 1
        //      }
        //  }

        break;
    case 'Itinerary_Stop':  
        $result = createItineraryStop($data['Value']);
        //  {
        //      "Type": "Itinerary_Stop",
        //      "Value": {
        //          "fk_itinerary_includes": 1,
        //          "type": "Train",
        //          "value": "Train 123",
        //          "booking_ref": "ABC123",
        //          "link": "http://example.com",
        //          "online_ticket": "base64_encoded_ticket_string",
        //          "start": "2025-03-21 10:00:00",
        //          "stop": "2025-03-21 12:00:00"
        //      }
        //  }

        break;
    case 'Itinerary_Transit':  
        $result = createItineraryTransit($data['Value']);
        //  {
        //      "Type": "Itinerary_Transit",
        //      "Value": {
        //          "fk_itinerary_has_assigned": 1,
        //          "fk_before": 1,
        //          "fk_after": 2,
        //          "method": "Train",
        //          "booking_ref": "TR123",
        //          "link": "http://example.com",
        //          "online_ticket": "base64_encoded_ticket_string",
        //          "start": "2025-03-21 13:00:00",
        //          "stop": "2025-03-21 15:00:00"
        //      }
        //  }
        
        break;
    default:
        $responseMessages[] = ["message" => "Invalid Type"];
        echo json_encode($responseMessages);
        exit;
}

if ($result && is_array($result) && isset($result['message'])) {
    $responseMessages[] = $result;
} elseif ($result) {
    $responseMessages[] = ["message" => "Creation successful"];
} else {
    $responseMessages[] = ["message" => "Error occurred during creation"];
}

echo json_encode($responseMessages);
exit;
?>
