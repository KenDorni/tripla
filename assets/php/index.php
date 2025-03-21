<?php
header('Content-Type: application/json');
session_start();

// Get the request method (POST, GET, PUT, DELETE)
$method = $_SERVER["REQUEST_METHOD"];

// Get the input data (this works for both GET and POST)
$data = json_decode(file_get_contents("php://input"), true);

$responseMessages = [];

// Validate that Type and Value are set
if (!isset($data['Type']) || !isset($data['Value'])) {
    $responseMessages[] = ["message" => "Invalid request: missing Type or Value"];
    echo json_encode($responseMessages);
    exit();
}

// Route based on the request method (POST, GET, PUT, DELETE)
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
        echo json_encode($responseMessages);
        break;
}

?>
