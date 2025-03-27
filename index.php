<?php
include "assets/php/db_functions.php";
include "assets/php/db_credentials.php";
include "assets/php/functions.php";

$page = $_GET["page"];



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- HEADER -->
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- Suchleiste -->
    <link rel="stylesheet" href="assets/css/searchField.css">
</head>
<body>
<header>
    <?php
    include "include/header.php";
    ?>
</header>
<main>
    <?php
    include "include/pages/" . $page . ".php";
    ?>
</main>
<footer>

</footer>