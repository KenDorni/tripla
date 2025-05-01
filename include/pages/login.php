<form method="post">
    <fieldset>
        <legend>Login</legend>
        <label>
            E-mail:
            <input type="text" name="emailAddress">
        </label>
        <label>
            Password:
            <input type="password" name="password">
        </label>
        <input type="submit" name="login" value="Login"><?php echo $message ?? '' ?><hr>
        <a href="?page=register">Register</a><hr>
        <a href="?page=ForgotPW">Forgot ?</a>
    </fieldset>
</form>
<?php
echo "<pre>" . print_r($_SESSION, true) . "</pre>";
