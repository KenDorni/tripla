<?php
include "assets/php/db_functions.php";
include "assets/php/db_credentials.php";
include "assets/php/functions.php";

session_start();

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}

$page = $_GET["page"];






if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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