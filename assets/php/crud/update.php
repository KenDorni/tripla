<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/up_functions.php');

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
            $result = updateAccount($data['Value'], $userId);
            break;
        case 'Itinerary':
            $result = updateItinerary($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Stop':
            $result = updateItineraryStop($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Transit':
            $result = updateItineraryTransit($data['Value'], $userId, $isLoggedIn);
            break;
        default:
            throw new Exception("Invalid Type");
    }

    if (isset($result['success'])) {
        $response = array_merge($response, $result);
    } else {
        $response['success'] = true;
        $response['message'] = "Update successful";
        $response['data'] = $result;
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Handle cookie updates for guests
if (!$isLoggedIn && $data['Type'] !== 'Account') {
    $cookieData = isset($_COOKIE['tripla_data']) ? json_decode($_COOKIE['tripla_data'], true) : [];
    
    if (isset($cookieData[$data['Type']])) {
        $updated = false;
        foreach ($cookieData[$data['Type']] as &$item) {
            if (matchesCookieItem($item, $data['Value'])) {
                $item = array_merge($item, $data['Value']);
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            setcookie('tripla_data', json_encode($cookieData), time() + (86400 * 30), "/");
            $response['message'] = ($response['message'] ?? '') . " (Updated in cookies)";
        }
    }
}

echo json_encode($response);
exit();

function matchesCookieItem($cookieItem, $updateData) {
    // Match on ID fields
    $idFields = [
        'Itinerary' => 'pk_itinerary',
        'Itinerary_Stop' => 'pk_Itinerary_Stop', 
        'Itinerary_Transit' => 'pk_itinerary_transit'
    ];
    
    $type = $updateData['Type'] ?? '';
    if (isset($idFields[$type]) && isset($updateData[$idFields[$type]])) {
        return $cookieItem[$idFields[$type]] === $updateData[$idFields[$type]];
    }
    
    return false;
}
?>