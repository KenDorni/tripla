<?php
if (!isset($_SESSION["user"])){
    header("Location: index.php?page=login");
}
?>
<h1>Account Management</h1><hr>
<h2>Details</h2><hr>
<label>
    E-mail Address:
    <input disabled>
</label>
<label>
    Username:
    <input disabled>
</label><hr>
<h2>Password</h2><hr>
<label>
    Reset Password:
    <input type="button" value="Reset Now">
</label>
<div id="pw-reset">
    <label>
        Old Password:
        <input type="password">
    </label>
    <label>
        New Password:
        <input type="password">
    </label>
    <label>
        Confirm Password:
        <input type="password">
    </label>
    <input type="button">
</div>