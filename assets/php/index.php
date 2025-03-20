<?php
header('Content-Type: application/json');
session_start();

$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"), true);
$responseMessages = []; // Initialize response messages array

if (!isset($data['Type']) || !isset($data['Value'])) { // Corrected from 'Values' to 'Value' to match Payload example
    $responseMessages[] = ["message" => "Invalid payload: missing Type or Value"];
    echo json_encode($responseMessages);
    exit();  // Stop further execution if payload is invalid
}

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
        $responseMessages[] = ["message" => "Method not allowed"];
        break;
}

// Return response messages as JSON
echo json_encode($responseMessages);
?>