<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/rd_functions.php');

header('Content-Type: application/json');

if (!isset($data['Type'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request: missing Type']);
    exit;
}

$value = isset($data['Value']) ? $data['Value'] : [];

switch ($data['Type']) {
    case 'Account':
        $result = readAccount($value);
        break;
        // {
        //     "Type": "Account",
        //     "Value": {
        //         "email_address": "user@example.com"
        //     }
        // }
    case 'Itinerary':
        $result = readItinerary($value);
        break;
        // {
        //     "Type": "Itinerary",
        //     "Value": {
        //         "fk_user_created": 1
        //     }
        // }
    case 'Itinerary_Stop':
        $result = readItineraryStop($value);
        break;
        // {
        //     "Type": "Itinerary_Stop",
        //     "Value": {
        //         "fk_itinerary_includes": 5
        //     }
        // }
    case 'Itinerary_Transit':
        $result = readItineraryTransit($value);
        break;
        // {
        //     "Type": "Itinerary_Transit",
        //     "Value": {
        //         "method": "Flight"
        //     }
        // }
    default:
        http_response_code(400);
        echo json_encode(['message' => 'Invalid Type']);
        exit;
}

if (isset($result['error'])) {
    http_response_code(500);
    echo json_encode(['message' => $result['error']]);
} else {
    echo json_encode([
        'message' => 'Data retrieved successfully',
        'data' => $result
    ]);
}
?>