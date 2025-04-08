<form method="post">
    <fieldset>
        <label>
            E-mail Address:
            <input type="text" name="emailAddress">
        </label><br>
        <label>
            Username :
            <input type="text" name="username" maxlength="64">
        </label><br>
        <label>
            Password:
            <input type="password" name="pw">
        </label><br>
        <label>
            Confirm password:
            <input type="password" name="pw-confirm">
        </label>
        <input type="submit">
    </fieldset>
</form>
<?php
function register($dbc, $emailAddress, $pw, $username)
{
    queryStatement($dbc, "INSERT INTO tripla.user(email_address, password, username) VALUES (?,?,?)", "sss", $emailAddress, $pw, $username);
}

function send_verification_mail(string $receiver)
{
    $_SESSION["OTP"] = rand(100000, 999999);

    $message = "<body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 2rem;'>
  <div style='max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #ddd; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);'>

    <!-- Header -->
    <div style='background-color: #4f46e5; padding: 1.5rem; text-align: center;'>
      <h1 style='color: white; margin: 0; font-size: 24px;'>Email Verification</h1>
    </div>

    <!-- Content -->
    <div style='padding: 2rem;'>
      <p style='font-size: 16px; color: #333;'>Hi there,</p>

      <p style='font-size: 16px; color: #333;'>
        Thank you for signing up. To complete your registration, please verify your email address using the one-time password (OTP) below:
      </p>

      <!-- OTP Box -->
      <div style='margin: 2rem 0; text-align: center;'>
        <div style='display: inline-block; background-color: #e0e7ff; color: #1e40af; padding: 1.5rem 2rem; font-size: 28px; font-weight: bold; border-radius: 8px; letter-spacing: 6px;'>
          <button  style='display: inline-block; background-color: inherit; color: inherit;font-size: 28px; font-weight: bold; border-radius: 8px; letter-spacing: 6px;border:none;cursor:pointer;' onclick='copyToClipboard()' id='otp' >918849</button>    
        </div>
      </div>

      <p style='font-size: 16px; color: #333;'>
        This code will expire in 10 minutes. If you did not request this, please ignore this email.
      </p>

      <p style='font-size: 16px; color: #333; margin-top: 2rem;'>
        Best regards,<br/>
        <strong>YourApp Team</strong>
      </p>
    </div>

    <!-- Footer -->
    <div style='background-color: #f9fafb; padding: 1rem; text-align: center; font-size: 12px; color: #888;'>
      Need help? Contact our support team.
    </div>

  </div>
<script>
function copyToClipboard() {
  // Get the text field
  let copyText = document.getElementById(`otp`);
  
  // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.innerText);
  
  // Alert the copied text
  alert(`Copied the text: ` + copyText.innerText);
}
</script>
</body>";

    $headers = [
        'From' => 'Tripla <no-reply@tripla.local>',
        'Reply-To' => "support@tripla.local",
        'MIME-Version' => "1.0",
        'Content-Type' => "text/html; charset=UTF-8",
        'X-Mailer' => 'PHP/' . phpversion()];

    mail($receiver, "Tripla account verification", $message, $headers);
}

?>