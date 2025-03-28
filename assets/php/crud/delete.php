<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/dt_functions.php');

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
            $result = deleteAccount($data['Value'], $userId);
            break;
        case 'Itinerary':
            $result = deleteItinerary($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Stop':
            $result = deleteItineraryStop($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Transit':
            $result = deleteItineraryTransit($data['Value'], $userId, $isLoggedIn);
            break;
        default:
            throw new Exception("Invalid Type");
    }

    if (isset($result['success'])) {
        $response = array_merge($response, $result);
    } else {
        $response['success'] = true;
        $response['message'] = "Deletion successful";
        $response['data'] = $result;
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Handle cookie deletions for guests
if (!$isLoggedIn && $data['Type'] !== 'Account') {
    $cookieData = isset($_COOKIE['tripla_data']) ? json_decode($_COOKIE['tripla_data'], true) : [];
    
    if (isset($cookieData[$data['Type']])) {
        $initialCount = count($cookieData[$data['Type']]);
        $cookieData[$data['Type']] = array_filter(
            $cookieData[$data['Type']], 
            function($item) use ($data) {
                return !matchesCookieItem($item, $data['Value']);
            }
        );
        
        if (count($cookieData[$data['Type']]) < $initialCount) {
            setcookie('tripla_data', json_encode($cookieData), time() + (86400 * 30), "/");
            $response['message'] = ($response['message'] ?? '') . " (Removed from cookies)";
            $response['success'] = true;
        }
    }
}

echo json_encode($response);
exit();

function matchesCookieItem($cookieItem, $deleteData) {
    $idFields = [
        'Itinerary' => 'pk_itinerary',
        'Itinerary_Stop' => 'pk_Itinerary_Stop',
        'Itinerary_Transit' => 'pk_itinerary_transit'
    ];
    
    $type = $deleteData['Type'] ?? '';
    if (isset($idFields[$type])) {
        return isset($deleteData[$idFields[$type]]) && 
               isset($cookieItem[$idFields[$type]]) && 
               $cookieItem[$idFields[$type]] === $deleteData[$idFields[$type]];
    }
    
    return false;
}
?>