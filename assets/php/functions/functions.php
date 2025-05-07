<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/php/phpmailer/src/Exception.php';
require 'assets/php/phpmailer/src/PHPMailer.php';
require 'assets/php/phpmailer/src/SMTP.php';

require_once(__DIR__ . '/../database/db_functions.php');

/**
 * Initialize foreign key session if not exists
 */
function initForeignKeySession() {
    if (!isset($_SESSION['foreign-key'])) {
        $_SESSION['foreign-key'] = [
            'Account' => null,
            'Itinerary' => null,
            'Itinerary_Stop' => null,
            'Itinerary_Transit' => null
        ];
    }
}

/**
 * Get foreign key from session
 * @param string $type Type of the foreign key (Account, Itinerary, etc.)
 * @param int $userId User ID to check ownership
 * @return mixed Foreign key value or null if not found
 * @throws Exception if type is invalid
 */
function getForeignKey($type, $userId = null) {
    initForeignKeySession();
    
    if (!in_array($type, ['Account', 'Itinerary', 'Itinerary_Stop', 'Itinerary_Transit'])) {
        throw new Exception("Invalid foreign key type: $type");
    }
    
    $foreignKey = $_SESSION['foreign-key'][$type];
    
    // For Account, we don't need to check userId
    if ($type !== 'Account' && $userId !== null && $foreignKey !== null) {
        // Verify ownership - this would typically be done in the database
        // but we're checking here for consistency
        $dbc = dbConnect();
        $query = "SELECT 1 FROM Itinerary WHERE pk_itinerary = ? AND fk_user_created = ?";
        $result = queryStatement($dbc, $query, 'ii', $foreignKey, $userId);
        
        if (!$result || mysqli_num_rows($result) === 0) {
            throw new Exception("Unauthorized access to $type");
        }
    }
    
    return $foreignKey;
}

/**
 * Set foreign key in session
 * @param string $type Type of the foreign key (Account, Itinerary, etc.)
 * @param mixed $foreignKey The foreign key value
 * @param int|null $userId User ID for ownership (null for Account)
 * @throws Exception if type is invalid or if trying to set a foreign key that exists
 */
function setForeignKey($type, $foreignKey, $userId = null) {
    initForeignKeySession();
    
    if (!in_array($type, ['Account', 'Itinerary', 'Itinerary_Stop', 'Itinerary_Transit'])) {
        throw new Exception("Invalid foreign key type: $type");
    }
    
    // Check if we're trying to set a foreign key that already exists
    if ($_SESSION['foreign-key'][$type] !== null) {
        throw new Exception("Foreign key for $type already exists");
    }
    
    $_SESSION['foreign-key'][$type] = $foreignKey;
    
    // For non-Account types, verify ownership
    if ($type !== 'Account' && $userId !== null) {
        $dbc = dbConnect();
        $query = "SELECT 1 FROM Itinerary WHERE pk_itinerary = ? AND fk_user_created = ?";
        $result = queryStatement($dbc, $query, 'ii', $foreignKey, $userId);
        
        if (!$result || mysqli_num_rows($result) === 0) {
            // Remove the foreign key if ownership verification fails
            $_SESSION['foreign-key'][$type] = null;
            throw new Exception("Unauthorized to set foreign key for $type");
        }
    }
}

/**
 * Remove foreign key from session
 * @param string $type Type of the foreign key (Account, Itinerary, etc.)
 * @return bool Returns true if foreign key was successfully removed, false if it didn't exist
 * @throws Exception if type is invalid
 */
function removeForeignKey($type) {
    initForeignKeySession();
    
    if (!in_array($type, ['Account', 'Itinerary', 'Itinerary_Stop', 'Itinerary_Transit'])) {
        throw new Exception("Invalid foreign key type: $type");
    }
    
    if ($_SESSION['foreign-key'][$type] === null) {
        return false; // Foreign key didn't exist
    }
    
    $_SESSION['foreign-key'][$type] = null;
    return true; // Successfully removed
}

/**
 * Check whether the username and password provided match
 * @param $dbc
 * @param $user
 * @param $pw
 * @return bool
 */
function verifyUser($dbc, $user, $pw): bool
{
    $query = "SELECT email_address, password FROM User WHERE email_address = ? ";
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
    $result = queryStatement($dbc, "SELECT email_address FROM User WHERE email_address = ?", "s", $user);

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
    $query = "SELECT pk_user, email_address, username, password FROM `User` WHERE email_address = ?";
    try {
        $result = queryStatement($dbc, $query, "s", $emailAddress);
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user data in the session
                $_SESSION['user'] = [
                    'id' => $user['pk_user'],
                    'email' => $emailAddress,
                    'username' => $user["username"]
                ];

                return true;
            } else {
                echo "Invalid password.";
                return false;
            }
        } else {
            echo "User not found.";
            return false;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function register($dbc, $emailAddress, $pw, $username)
{
    queryStatement($dbc, "INSERT INTO User(email_address, password, username) VALUES (?,?,?)", "sss", $emailAddress, password_hash($pw, PASSWORD_DEFAULT), $username);

    //confirmation message
    $message = "Thank you for signing up to Tripla";

    /*$headers = [
        'From' => 'Tripla <no-reply@tripla.local>',
        'Reply-To' => "support@tripla.local",
        'MIME-Version' => "1.0",
        'Content-Type' => "text/html; charset=UTF-8",
        'X-Mailer' => 'PHP/' . phpversion()];*/

    //mail($emailAddress, "Tripla account verified", $message, $headers);
    send_mail($emailAddress, $message);
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
          <button  style='display: inline-block; background-color: inherit; color: inherit;font-size: 28px; font-weight: bold; 
          border-radius: 8px; letter-spacing: 6px;border:none;cursor:pointer;' onclick='copyToClipboard()' id='otp' >{$_SESSION['OTP']}</button>    
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

//<<<<<<< HEAD
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                        // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server
        $mail->SMTPAuth = true;               // Enable SMTP authentication
        $mail->Username = 'tripla.welcome@gmail.com';   // SMTP username
        $mail->Password = 'Aaa123456+-';    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // Use 'tls' for Port 587 or 'ssl' for 465
        $mail->Port = 465;              // TCP port to connect to

        // Recipients
        $mail->setFrom('tripla.welcome@gmail.com', 'Tripla');
        $mail->addAddress($receiver, 'Recipient Name'); // Add a recipient

        // Content
        $mail->isHTML(true);                    // Set email format to HTML
        $mail->Subject = 'Welcome to Tripla - Account verification';
        $mail->Body = $message;
        //$mail->AltBody = 'This is a test email sent using PHPMailer (plain text).';

        $mail->send();
        echo 'Message has been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
//=======
    send_mail($receiver, $message);
//>>>>>>> pages2

    /*$headers = [
        'From' => 'Tripla <no-reply@tripla.local>',
        'Reply-To' => "support@tripla.local",
        'MIME-Version' => "1.0",
        'Content-Type' => "text/html; charset=UTF-8",
        'X-Mailer' => 'PHP/' . phpversion()];
*/
    //mail($receiver, "Tripla account verification", $message, $headers);
//<<<<<<< HEAD
//=======
}

function send_mail($receiver, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                        // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server
        $mail->SMTPAuth = true;               // Enable SMTP authentication
        $mail->Username = 'tripla.welcome@gmail.com';   // SMTP username
        $mail->Password = 'hucr dray bqwm oswt';    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // Use 'tls' for Port 587 or 'ssl' for 465
        $mail->Port = 465;              // TCP port to connect to

        // Recipients
        $mail->setFrom('tripla.welcome@gmail.com', 'Tripla');
        $mail->addAddress($receiver, 'Recipient Name'); // Add a recipient

        // Content
        $mail->isHTML(true);                    // Set email format to HTML
        $mail->Subject = 'Welcome to Tripla - Account verification';
        $mail->Body = $message;
        //$mail->AltBody = 'This is a test email sent using PHPMailer (plain text).';

        $mail->send();
        echo 'Message has been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
//>>>>>>> pages2
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