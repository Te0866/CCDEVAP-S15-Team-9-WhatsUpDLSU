<?php

class Database
{
    private static ?mysqli $instance = null;

    public static function connection(): mysqli
    {
        if (self::$instance === null) {
            $host = "localhost";
            $user = "root";
            $pass = "iPqfwfLp5FKk";
            $name = "whatsupdlsu";

            $conn = mysqli_connect($host, $user, $pass, $name);

            if (!$conn) {
                error_log("DB connection failed: " . mysqli_connect_error());
                die("A database error occurred.");
            }

            self::$instance = $conn;
        }

        return self::$instance;
    }

   
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
