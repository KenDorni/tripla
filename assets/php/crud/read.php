<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/rd_functions.php');

$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

try {
    $isLoggedIn = isset($_SESSION['user_id']);
    $userId = $isLoggedIn ? $_SESSION['user_id'] : null;

    switch ($data['Type']) {
        case 'Account':
            $result = getAccount($data['Value'], $userId);
            break;
        case 'Itinerary':
            $result = getItinerary($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Stop':
            $result = getItineraryStop($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Transit':
            $result = getItineraryTransit($data['Value'], $userId, $isLoggedIn);
            break;
        default:
            throw new Exception("Invalid Type");
    }

    if (isset($result['success'])) {
        $response = array_merge($response, $result);
    } else {
        $response['success'] = true;
        $response['message'] = "Data retrieved successfully";
        $response['data'] = $result;
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// If not logged in, check cookies for data
if (!$isLoggedIn && $data['Type'] !== 'Account') {
    $cookieData = isset($_COOKIE['tripla_data']) ? json_decode($_COOKIE['tripla_data'], true) : [];
    
    if (isset($cookieData[$data['Type']])) {
        $filteredData = filterCookieData($cookieData[$data['Type']], $data['Value']);
        if (!empty($filteredData)) {
            $response['data'] = array_merge($response['data'], $filteredData);
            $response['message'] = "Combined database and cookie data";
        }
    }
}

echo json_encode($response);
exit();

function filterCookieData($cookieItems, $filterCriteria) {
    return array_filter($cookieItems, function($item) use ($filterCriteria) {
        foreach ($filterCriteria as $key => $value) {
            if (isset($item[$key]) && $item[$key] != $value) {
                return false;
            }
        }
        return true;
    });
}
?>