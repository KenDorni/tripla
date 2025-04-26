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


if (isset($_POST["emailAddress"]) && isset($_POST["username"]) && isset($_POST["pw"]) && isset($_POST["pw-confirm"]) && isset($_POST["register"]) ){
    if ($_POST["pw"] == $_POST["pw-confirm"]){
        send_verification_mail($_POST["emailAddress"]);
    }else{
        $message = "Passwords don't match";
    }
}else{
    $message = "Please fill out all the fields";
}


if (isset($_SESSION["OTP"])){
    $page = "verification";

    if (isset($_POST["chars"])){
        echo "<pre>" . print_r(implode($_POST["chars"]), true) . "</pre>";
        if ($_SESSION["OTP"] == implode($_POST["chars"])){
            register($dbc, $_POST["emailAddress"], password_hash($_POST["pw"], PASSWORD_DEFAULT), $_POST["username"]);
            //session_unset();

            login($dbc, $_POST["emailAddress"], $_POST["pw"]);
        }
    }
}

//echo "<pre>" . print_r($_SESSION, true) . "</pre>";

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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="//code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <script src="//code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
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