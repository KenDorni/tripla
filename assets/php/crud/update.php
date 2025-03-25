<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/up_functions.php');

header('Content-Type: application/json');

if (!isset($data['Type']) || !isset($data['Value'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request: missing Type or Value']);
    exit;
}

switch ($data['Type']) {
    case 'Account':
        $result = updateAccount($data['Value']);
        break;
    case 'Itinerary':
        $result = updateItinerary($data['Value']);
        break;
    case 'Itinerary_Stop':
        $result = updateItineraryStop($data['Value']);
        break;
    case 'Itinerary_Transit':
        $result = updateItineraryTransit($data['Value']);
        break;
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
        'message' => 'Update successful',
        'affected_rows' => $result
    ]);
}
?>