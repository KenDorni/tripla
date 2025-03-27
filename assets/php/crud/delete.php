<?php
// php/crud/delete.php
require_once(__DIR__ . '/../functions/dt_functions.php');

if (!isset($data['Type']) || !isset($data['Value'])) {
    $responseMessages[] = ["message" => "Invalid payload: missing Type or Value"];
    echo json_encode($responseMessages);
    exit;
}

switch ($data['Type']) {
    case 'Account':
        $result = deleteAccount($data['Value']);
        //  {
        //      "Type": "Account",
        //      "Value": {
        //          "pk_user": 1 // Required for delete
        //      }
        //  }
        break;
    case 'Itinerary':
        $result = deleteItinerary($data['Value']);
        //  {
        //      "Type": "Itinerary",
        //      "Value": {
        //          "pk_itinerary": 1 // Required for delete
        //      }
        //  }
        break;
    case 'Itinerary_Stop':
        $result = deleteItineraryStop($data['Value']);
        //  {
        //      "Type": "Itinerary_Stop",
        //      "Value": {
        //          "pk_itinerary_stop": 1 // Required for delete
        //      }
        //  }
        break;
    case 'Itinerary_Transit':
        $result = deleteItineraryTransit($data['Value']);
        //  {
        //      "Type": "Itinerary_Transit",
        //      "Value": {
        //          "pk_itinerary_transit": 1 // Required for delete
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
    $responseMessages[] = ["message" => "Deletion successful"];
} else {
    $responseMessages[] = ["message" => "Error occurred during deletion"];
}

echo json_encode($responseMessages);
exit;
?>