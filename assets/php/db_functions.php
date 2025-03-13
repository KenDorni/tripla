<?php
/**
 * Creates a mysqli database connection.
 * @return false|mysqli|void
 */
function dbConnect()
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (mysqli_connect_error()) {
        die("ERROR: Cannot connect to" . DB_NAME . " ErrorCode => " . mysqli_connect_errno());
    }

    mysqli_set_charset($dbc, 'utf8');

    return $dbc;
}

/**
 * Executes SQL-Query and protects from SQL-Injection
 * !Important don't forget to mysqli_free_result after function call
 * @param false|mysqli $dbc Database connection
 * @param string $query Query that gets executed
 * @param string $type A string that contains one or more characters which specify the types for the corresponding bind variables:
 * <table>
 *     <tr>
 *         <td>Type specification chars</td>
 *     </tr>
 * <tr>
 *     <td>Character</td>
 *     <td>Description</td>
 * </tr>
 * <tr>
 *     <td>i</td>
 *     <td>corresponding variable has type integer</td>
 * </tr>
 * <tr>
 *     <td>d</td>
 *     <td>corresponding variable has type double</td>
 * </tr>
 * <tr>
 *     <td>s</td>
 *     <td>corresponding variable has type string</td>
 * </tr>
 * <tr>
 *     <td>b</td>
 *     <td>corresponding variable is a blob and will be sent in packets</td>
 * </tr>
 * </table>
 * @param mixed &$vars parameters that are inserted into the sql query
 * @return bool|mysqli_result returns mysqli_result for successful SELECT, SHOW, DESCRIBE or EXPLAIN - true for other successful Queries and false on error
 * @throws Exception on wrong sql query
 * @throws Exception on empty data type
 * @throws Exception on statement error
 */
function queryStatement($dbc, string $query, string $type = "", ...$vars)
{
    try {
        $statement = mysqli_prepare($dbc, $query);

        if (mysqli_errno($dbc)) {
            throw new Exception("Wrong SQL: $query Error: " . mysqli_error($dbc));
        }

        if ($type === "" && !empty($vars)) {
            throw new Exception("Data type cannot be an empty string when variables are provided");
        }

        if ($type != "" && !empty($vars)) {
            if (!mysqli_stmt_bind_param($statement, $type, ...$vars)) {
                throw new Exception("Error binding parameters: " . mysqli_stmt_error($statement));
            }
        }

        if (mysqli_stmt_errno($statement)) {
            throw new Exception("Wrong Statement: Error: " . mysqli_stmt_error($statement));
        }

        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);

        if (mysqli_stmt_errno($statement) == 0 && !$result) {
            mysqli_stmt_close($statement);
            return true;
        }

        mysqli_stmt_close($statement);
        return $result;
    } catch (Exception $exception) {
        echo "Caught Exception: " . $exception->getMessage() . "\n";
        echo "Trace: " . $exception->getTraceAsString() . "\n";
        return false;
    }
}


function fetchAllFields($mysqli_result): array
{
    $rows = [];
    if ($mysqli_result instanceof mysqli_result) {
        while ($row = $mysqli_result->fetch_assoc()) {
            $rows[] = $row; // Add each row as an associative array
        }
    }
    return $rows;
}