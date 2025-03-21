<?php
require_once(__DIR__ . '/../functions/rd_functions.php');
header('Content-Type: application/json');

$type = isset($_GET['Type']) ? $_GET['Type'] : '';
$value = isset($_GET['Value']) ? $_GET['Value'] : null;

if (empty($type) || empty($value)) {
    echo json_encode(['message' => 'Invalid request: missing Type or Value']);
    exit();
}

switch ($type) {
    case 'Itinerary':
        $itinerary_id = isset($value['pk_itinerary']) ? $value['pk_itinerary'] : null;
        if ($itinerary_id) {
            $result = getItineraryById($itinerary_id);
            echo json_encode($result);
        } else {
            echo json_encode(['message' => 'Invalid Itinerary ID']);
        }
        break;

    case 'Itinerary_Stop':
        $itinerary_id = isset($value['fk_itinerary_includes']) ? $value['fk_itinerary_includes'] : null;
        if ($itinerary_id) {
            $result = getAllItineraryStops($itinerary_id);
            echo json_encode($result);
        } else {
            echo json_encode(['message' => 'Invalid Itinerary Stop ID']);
        }
        break;

    case 'Itinerary_Transit':
        $transit_id = isset($value['pk_itinerary_transit']) ? $value['pk_itinerary_transit'] : null;
        if ($transit_id) {
            $result = getItineraryTransitById($transit_id);
            echo json_encode($result);
        } else {
            echo json_encode(['message' => 'Invalid Itinerary Transit ID']);
        }
        break;

    default:
        echo json_encode(['message' => 'Invalid Type']);
        break;
}
?>
