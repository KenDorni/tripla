<div class="container">
    <form method="post">
        <fieldset>
            <legend>Create Account</legend>
            <label>
                E-mail Address:
                <input type="text" name="emailAddress" placeholder="Enter your email">
            </label>
            <label>
                Username:
                <input type="text" name="username" maxlength="64" placeholder="Choose a username">
            </label>
            <label>
                Password:
                <input type="password" name="pw" placeholder="Enter your password">
            </label>
            <label>
                Confirm password:
                <input type="password" name="pw-confirm" placeholder="Confirm your password">
            </label>
            <input type="submit" name="register" value="Create Account">
            
            <?php if (isset($message)): ?>
                <div class="<?php echo strpos($message, 'success') !== false ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <hr>
            <a href="?page=login">Already have an account? Login</a>
        </fieldset>
    </form>
</div>