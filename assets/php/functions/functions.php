<?php

require_once(__DIR__ . '/../database/db_functions.php');

/**
 * Check whether the username and password provided match
 * @param $dbc
 * @param $user
 * @param $pw
 * @return bool
 */
function verifyUser($dbc, $user, $pw): bool
{
    $query = "SELECT email_address, password FROM Operator WHERE email_address = ? ";
    $result = queryStatement($dbc, $query, "s", $user);

    $row = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    return password_verify($pw, $row["passwordHash"]);
}

/**
 * Check whether user exists or not
 * @param $dbc
 * @param $user
 * @return bool
 */
function userExists($dbc, $user): bool
{
    $result = queryStatement($dbc, "SELECT email_address FROM Operator WHERE email_address = ?", "s", $user);

    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    if (empty($row["emailAddress"]) && is_null($row["emailAddress"])) {
        return FALSE;
    }
    return TRUE;
}

/**
 * Login check
 * @param $dbc
 * @param $username
 * @param $password
 * @return string|true returns an error message or true on success
 */
function accountLoginHandler($dbc, $username, $password)
{
    if (!userExists($dbc, $username)) {
        return "Username does not exist";
    } elseif (verifyUser($dbc, $username, $password)) {
        return TRUE;
    } else {
        return "Wrong password";
    }
}

/**
 * Login function to authenticate users
 * @param string $emailAddress
 * @param string $password
 * @return bool
 */
function login($dbc, string $emailAddress, string $password): bool
{
    // Connect to the database
    //$dbc = dbConnect();
    if (!$dbc) {
        die("Database connection failed.");
    }

    // Query to find the user by email
    $query = "SELECT pk_user, email_address, username, password FROM Operator WHERE email_address = ?";
    try {
        $result = queryStatement($dbc, $query, "s", $emailAddress);
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // Verify the password
            if (password_verify($password, $user['passwordHash'])) {
                // Store user data in the session
                $_SESSION['user'] = [
                    'id' => $user['pk_user'],
                    'email' => $emailAddress,
                    'username' => $user["username"]
                ];

                return true;
            } else {
                //echo "Invalid password.";
                return false;
            }
        } else {
            //echo "User not found.";
            return false;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function register($dbc, $emailAddress, $pw, $username)
{
    queryStatement($dbc, "INSERT INTO tripla.user(email_address, password, username) VALUES (?,?,?)", "sss", $emailAddress, password_hash($pw, PASSWORD_DEFAULT), $username);

    //confirmation message
    $message = "Thank you for signing up to Tripla";

    $headers = [
        'From' => 'Tripla <no-reply@tripla.local>',
        'Reply-To' => "support@tripla.local",
        'MIME-Version' => "1.0",
        'Content-Type' => "text/html; charset=UTF-8",
        'X-Mailer' => 'PHP/' . phpversion()];

    mail($emailAddress, "Tripla account verified", $message, $headers);
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

function generateSimpleTable($result)
{
    echo "<table class='generic-table'>";
    echo "<thead>";
    $fieldinfo = mysqli_fetch_fields($result);
    foreach ($fieldinfo as $field) {
        echo "<th>" . $field->name . "</th>";
    }
    echo "</thead>";
    echo "<tbody>";
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
        $row = mysqli_fetch_assoc($result);
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}