<?php
session_start();
header('Content-Type: application/json');

$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"), true);

$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

if (!$data || !isset($data['Type']) || !isset($data['Value'])) {
    $response['message'] = "Invalid request: missing Type or Value";
    echo json_encode($response);
    exit();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

try {
    switch ($method) {
        case "POST":
            include(__DIR__ . "/crud/create.php");
            break;
        case "GET":
            include(__DIR__ . "/crud/read.php");
            break;
        case "PUT":
            include(__DIR__ . "/crud/update.php");
            break;
        case "DELETE":
            include(__DIR__ . "/crud/delete.php");
            break;
        default:
            http_response_code(405);
            $response['message'] = "Method not allowed";
            break;
    }
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);
exit();
?>