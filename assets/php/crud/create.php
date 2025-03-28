<?php
require_once(__DIR__ . '/../database/db_functions.php');
require_once(__DIR__ . '/../functions/ct_functions.php');

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
            $result = createAccount($data['Value']);
            break;
        case 'Itinerary':
            $result = createItinerary($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Stop':
            $result = createItineraryStop($data['Value'], $userId, $isLoggedIn);
            break;
        case 'Itinerary_Transit':
            $result = createItineraryTransit($data['Value'], $userId, $isLoggedIn);
            break;
        default:
            throw new Exception("Invalid Type");
    }
    
    if (isset($result['success'])) {
        $response = array_merge($response, $result);
    } else {
        $response['success'] = true;
        $response['message'] = "Operation completed successfully";
        $response['data'] = $result;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// If not logged in, store data in cookies
if (!$isLoggedIn && $data['Type'] !== 'Account') {
    $cookieData = isset($_COOKIE['tripla_data']) ? json_decode($_COOKIE['tripla_data'], true) : [];
    $cookieData[$data['Type']][] = $data['Value'];
    setcookie('tripla_data', json_encode($cookieData), time() + (86400 * 30), "/");
    $response['message'] .= " (Saved to cookies - login to save permanently)";
}

echo json_encode($response);
exit();
?>