<?php
if (!isset($_SESSION["user"])){
    header("Location: index.php?page=login");
}
?>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h1>Account Management</h1>
                    <hr>
                    <h2>Details</h2>
                    <hr>
                    <label>
                        E-mail Address:
                        <input disabled value="<?php echo $_SESSION["user"]["email"]; ?>">
                    </label>
                    <label>
                        Username:
                        <input disabled value="<?php echo $_SESSION["user"]["username"]; ?>">
                    </label>
                    <hr>
                    <h2>Password</h2>
                    <hr>
                    <label>
                        Reset Password:
                    </label>
                    <div id="pw-reset">
                        <label>
                            Old Password:
                            <input type="password" id="old-password">
                        </label>
                        <label>
                            New Password:
                            <input type="password" id="new-password">
                        </label>
                        <label>
                            Confirm Password:
                            <input type="password" id="confirm-password">
                        </label>
                        <input type="button" value="Change Password" id="change-password-btn">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>