<?php
require_once(__DIR__ . '/db_credentials.php');

function dbConnect()
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (mysqli_connect_error()) {
        die("ERROR: Cannot connect to " . DB_NAME . " ErrorCode => " . mysqli_connect_errno());
    }

    mysqli_set_charset($dbc, 'utf8');

    return $dbc;
}

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
            $rows[] = $row;
        }
    }
    return $rows;
}
?>
