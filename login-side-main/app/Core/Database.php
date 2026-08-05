<?php

/**
 * Single point of DB connection.
 * Replaces the old dbconnection.php require-everywhere pattern.
 * Every Model goes through here instead of touching mysqli_connect directly.
 */
class Database
{
    private static ?mysqli $instance = null;

    public static function connection(): mysqli
    {
        if (self::$instance === null) {
            // In a real deploy, pull these from environment variables / a
            // .env file that is NOT committed to git, e.g. getenv('DB_PASS').
            $host = "localhost";
            $user = "root";
            $pass = "iPqfwfLp5FKk";
            $name = "whatsupdlsu";

            $conn = mysqli_connect($host, $user, $pass, $name);

            if (!$conn) {
                // Don't leak connection details to the browser in production;
                // log the real error and show a generic message instead.
                error_log("DB connection failed: " . mysqli_connect_error());
                die("A database error occurred.");
            }

            mysqli_query($conn, "SET time_zone = '+08:00';");

            self::$instance = $conn;
        }

        return self::$instance;
    }

    /**
     * Convenience helper: prepare + bind + execute + get_result in one call.
     * $types is the mysqli bind_param type string, e.g. "is".
     */
    public static function query(string $sql, string $types = "", array $params = []): mysqli_result|bool
    {
        $conn = self::connection();
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($conn));
            die("A database error occurred.");
        }

        if ($types !== "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
}
