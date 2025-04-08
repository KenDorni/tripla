<?php
include "assets/php/database/db_functions.php";
include "assets/php/database/db_credentials.php";
include "assets/php/functions/functions.php";

session_start();

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}


if (!isset($_GET["page"])) {
    $page = "welcome";
} else {
    $page = $_GET["page"];
}

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

$dbc = dbConnect();

// Handle the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login"])) {
    $emailAddress = $_POST['emailAddress'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($emailAddress) && !empty($password)) {
        if (login($dbc, $emailAddress, $password)) {
            $page = "welcome";
        } else {
            $message = "Login failed. Please check your credentials.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

if (isset($_SESSION["OTP"])){
    $page = "verification";

    if ($_SESSION["OTP"] == $_POST["OTP"]){
        register($dbc, $_POST["emailAddress"], password_hash($_POST["pw"], PASSWORD_DEFAULT), $_POST["username"]);
        session_unset();

        login($dbc, $_POST["emailAddress"], $_POST["pw"]);
    }
}

mysqli_close($dbc);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" href="assets/images/icon/tripla-icon.png">
    <!-- HEADER -->
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- Suchleiste -->
    <link rel="stylesheet" href="assets/css/searchField.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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