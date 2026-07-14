<?php

require_once __DIR__ . "/../Core/Database.php";

/**
 * Registration-side queries against `users`.
 */
class User
{
    public static function usernameExists(string $username): bool
    {
        $result = Database::query(
            "SELECT USER_ID FROM users WHERE USER_NAME = ?",
            "s",
            [$username]
        );

        return mysqli_num_rows($result) > 0;
    }

    /**
     * Creates a new user. Returns the new USER_ID on success, or false
     * on failure.
     */
    public static function create(string $username, string $plainPassword): int|false
    {
        $conn = Database::connection();

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users (USER_NAME, PASSWORD, ROLE, CREATED_AT, STATUS) 
             VALUES (?, ?, 'USER', CURDATE(), 'ACTIVE')"
        );

        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($conn));
            return false;
        }

        mysqli_stmt_bind_param($stmt, "ss", $username, $plainPassword);

        if (!mysqli_stmt_execute($stmt)) {
            error_log("Insert failed: " . mysqli_error($conn));
            return false;
        }

        return mysqli_insert_id($conn);
    }
}
