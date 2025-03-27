<?php
header('Content-Type: application/json');
session_start();

$method = $_SERVER["REQUEST_METHOD"];

$data = json_decode(file_get_contents("php://input"), true);

$responseMessages = [];

if (!isset($data['Type']) || !isset($data['Value'])) {
    $responseMessages[] = ["message" => "Invalid request: missing Type or Value"];
    echo json_encode($responseMessages);
    exit();
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
        echo json_encode($responseMessages);
        break;
}

?>
