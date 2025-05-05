<div class="container">
    <form method="post">
        <fieldset>
            <legend>Login</legend>
            <label>
                E-mail:
                <input type="text" name="emailAddress" placeholder="Enter your email">
            </label>
            <label>
                Password:
                <input type="password" name="password" placeholder="Enter your password">
            </label>
            <input type="submit" name="login" value="Login">
            
            <?php if (isset($message)): ?>
                <div class="<?php echo strpos($message, 'success') !== false ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <hr>
            <a href="?page=register">Create an account</a>
            <hr>
            <a href="?page=ForgotPW">Forgot password?</a>
        </fieldset>
    </form>
</div>

<?php
// For development only, remove in production
if (isset($_SESSION) && !empty($_SESSION)) {
    echo "<div style='margin-top: 20px; font-size: 12px; color: #777;'><pre>" . print_r($_SESSION, true) . "</pre></div>";
}
?>