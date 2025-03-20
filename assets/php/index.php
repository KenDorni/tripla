<?php
header('Content-Type: application/json');
session_start();

$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"), true);
$responseMessages = [];

switch ($method) {
    case "POST":
        include __DIR__ . "/php/crud/create.php";
        break;
    case "GET":
        include __DIR__ . "/php/crud/read.php";
        break;
    case "PUT":
        include __DIR__ . "/php/crud/update.php";
        break;
    case "DELETE":
        include __DIR__ . "/php/crud/delete.php";
        break;
    default:
        http_response_code(405);
        $responseMessages[] = ["message" => "Method not allowed"];
        break;
}

echo json_encode($responseMessages);

?>