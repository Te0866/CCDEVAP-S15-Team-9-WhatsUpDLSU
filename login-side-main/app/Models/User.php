<?php

require_once __DIR__ . "/../Core/Database.php";

/**
 * Registration-side queries against `users`.
 *
 * NOTE: I'm assuming your `users` table has a NAME column for full name
 * and a USER_TYPE column that distinguishes user roles (e.g. 'user' vs
 * 'admin' vs 'org'). If your actual column names differ, the ONLY places
 * that need updating are the SQL strings below — nothing in the
 * Controller needs to change.
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
     * on failure. Password is stored as plaintext to match how the rest
     * of the app currently handles it. USER_TYPE is always 'user' —
     * this endpoint only ever creates ordinary student accounts.
     */
    public static function create(string $name, string $username, string $plainPassword): int|false
    {
        $conn = Database::connection();
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users (NAME, USER_NAME, PASSWORD, USER_TYPE) VALUES (?, ?, ?, 'user')"
        );

        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($conn));
            return false;
        }

        mysqli_stmt_bind_param($stmt, "sss", $name, $username, $plainPassword);

        if (!mysqli_stmt_execute($stmt)) {
            error_log("Insert failed: " . mysqli_error($conn));
            return false;
        }

        return mysqli_insert_id($conn);
    }
}
