<?php

require_once __DIR__ . "/../Core/Database.php";

/**
 * All queries against `users` live here. Controllers never write SQL.
 */
class User
{
    public static function findById(int $userId): ?array
    {
        $result = Database::query(
            "SELECT * FROM users WHERE USER_ID = ?",
            "i",
            [$userId]
        );

        $user = mysqli_fetch_assoc($result);
        return $user ?: null;
    }

    /**
     * Returns the path to this user's profile picture, falling back to a
     * default image if none has been uploaded. Mirrors the logic that used
     * to live in profile-picture.php.
     */
    public static function profilePicturePath(int $userId): string
    {
        foreach (['png', 'jpg'] as $ext) {
            $relative = "profile-pictures/{$userId}/pfp.{$ext}";
            if (file_exists(__DIR__ . "/../../{$relative}")) {
                return $relative;
            }
        }

        return "img/default-profile.png";
    }

    public static function updateProfile(int $userId, string $username, string $plainPassword): bool
    {
        // Improvement over the original: hash the password instead of
        // storing it in plaintext. login.php will need to switch to
        // password_verify() to match.
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $conn = Database::connection();
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users SET USER_NAME = ?, PASSWORD = ? WHERE USER_ID = ?"
        );

        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($conn));
            return false;
        }

        mysqli_stmt_bind_param($stmt, "ssi", $username, $hash, $userId);
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Handles the uploaded file for a profile picture. Returns an array
     * [success, error] so the controller can decide how to respond.
     */
    public static function saveProfileImage(int $userId, array $file): array
    {
        $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg'];
        $mimeType = mime_content_type($file['tmp_name']);

        if (!isset($allowedTypes[$mimeType])) {
            return [false, "Only PNG and JPG images are allowed."];
        }

        $extension = $allowedTypes[$mimeType];
        $targetDir = __DIR__ . "/../../profile-pictures/{$userId}/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        foreach (['png', 'jpg'] as $ext) {
            $existing = $targetDir . "pfp.{$ext}";
            if (file_exists($existing)) {
                unlink($existing);
            }
        }

        $targetPath = $targetDir . "pfp.{$extension}";

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return [false, "Failed to save uploaded image."];
        }

        return [true, null];
    }
}
