<?php
include "assets/php/database/db_functions.php";
include "assets/php/database/db_credentials.php";
include "assets/php/functions/functions.php";

if (!isset($_GET["page"])){
    $page = "searchField";
}else{
    $page = $_GET["page"];
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trippla</title>
</head>
<body>
<header>
    <?php

    ?>
</header>
<main>
    <?php
    include "include/pages/" . $page . ".php";
    ?>
</main>
<footer>

</footer>
</body>
</html>