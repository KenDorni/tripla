<?php

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "POST":
        include "create.php";
        break;
    case "GET":
        include "read.php";
        break;
    case "PUT":
        include "update.php";
        break;
    case "DELETE":
        include "delete.php";
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed"));
        break;
}

?>