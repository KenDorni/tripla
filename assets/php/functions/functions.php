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