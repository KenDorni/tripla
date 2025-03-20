<?php
// Include the configuration file where your DB credentials are stored
require_once(__DIR__ . '/db_credentials.php');  // Path corrected - from index.php

/**
 * Creates a mysqli database connection.
 * @return false|mysqli|void
 */
function dbConnect()
{
    // Connect to the database using constants from db_credentials.php
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // If the connection fails, die and print an error message
    if (mysqli_connect_error()) {
        die("ERROR: Cannot connect to " . DB_NAME . " ErrorCode => " . mysqli_connect_errno());
    }

    // Set the character set to utf8
    mysqli_set_charset($dbc, 'utf8');

    return $dbc;
}

/**
 * Executes SQL-Query and protects from SQL-Injection.
 * Important: Don't forget to mysqli_free_result after function call.
 * @param false|mysqli $dbc Database connection
 * @param string $query Query that gets executed
 * @param string $type A string that contains one or more characters which specify the types for the corresponding bind variables.
 * @param mixed &$vars Parameters that are inserted into the SQL query
 * @return bool|mysqli_result Returns mysqli_result for successful SELECT, SHOW, DESCRIBE, or EXPLAIN queries - true for other successful Queries and false on error.
 * @throws Exception on wrong SQL query
 * @throws Exception on empty data type
 * @throws Exception on statement error
 */
function queryStatement($dbc, string $query, string $type = "", ...$vars)
{
    try {
        // Prepare the SQL statement
        $statement = mysqli_prepare($dbc, $query);

        // Check for errors in SQL statement preparation
        if (mysqli_errno($dbc)) {
            throw new Exception("Wrong SQL: $query Error: " . mysqli_error($dbc));
        }

        // If type is not provided but variables are, throw an error
        if ($type === "" && !empty($vars)) {
            throw new Exception("Data type cannot be an empty string when variables are provided");
        }

        // If a type is provided, bind the parameters
        if ($type != "" && !empty($vars)) {
            if (!mysqli_stmt_bind_param($statement, $type, ...$vars)) {
                throw new Exception("Error binding parameters: " . mysqli_stmt_error($statement));
            }
        }

        // Check for errors in statement preparation
        if (mysqli_stmt_errno($statement)) {
            throw new Exception("Wrong Statement: Error: " . mysqli_stmt_error($statement));
        }

        // Execute the statement
        mysqli_stmt_execute($statement);

        // Get the result of the query execution
        $result = mysqli_stmt_get_result($statement);

        // If no result is returned (e.g., for INSERT, UPDATE), return true
        if (mysqli_stmt_errno($statement) == 0 && !$result) {
            mysqli_stmt_close($statement);
            return true;
        }

        // Close the statement and return the result
        mysqli_stmt_close($statement);
        return $result;
    } catch (Exception $exception) {
        // Print exception details for debugging
        echo "Caught Exception: " . $exception->getMessage() . "\n";
        echo "Trace: " . $exception->getTraceAsString() . "\n";
        return false;
    }
}

/**
 * Fetches all rows from a mysqli_result object as an associative array.
 * @param mysqli_result $mysqli_result The result from a SQL query.
 * @return array An array of associative arrays, each representing a row from the result set.
 */
function fetchAllFields($mysqli_result): array
{
    $rows = [];
    // Check if the result is a valid mysqli_result
    if ($mysqli_result instanceof mysqli_result) {
        // Loop through the rows and fetch them as associative arrays
        while ($row = $mysqli_result->fetch_assoc()) {
            $rows[] = $row;  // Add each row to the rows array
        }
    }
    return $rows;
}
?>