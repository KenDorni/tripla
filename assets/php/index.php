<?php

$method = $_SERVER["REQUEST_METHOD"];
require_once "functions/functions.php";

switch ($method) {
    case "POST":
        include "crud/create.php";
        break;
    case "GET":
        include "crud/read.php";
        break;
    case "PUT":
        include "crud/update.php";
        break;
    case "DELETE":
        include "crud/delete.php";
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed"));
        break;
}

?>