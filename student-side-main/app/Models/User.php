<?php

require_once __DIR__ . "/../Core/Database.php";

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

    
    public static function profilePicturePath(int $userId): string
    {
        $diskDir = __DIR__ . "/../../../profile-pictures/{$userId}/";
        $webDir = "../profile-pictures/{$userId}/";

        foreach (['pfp.png', 'pfp.jpg'] as $filename) {
            if (file_exists($diskDir . $filename)) {
                return $webDir . $filename;
            }
        }

        return "../profile-pictures/default-profile.png";
    }

    public static function updateProfile(int $userId, string $username, string $plainPassword = ''): bool
{
    $conn = Database::connection();

    if ($plainPassword === '') {
        // No new password given — update the username only.
        $stmt = mysqli_prepare($conn, "UPDATE users SET USER_NAME = ? WHERE USER_ID = ?");

        if (!$stmt) {
            error_log("Prepare failed: " . mysqli_error($conn));
            return false;
        }

        mysqli_stmt_bind_param($stmt, "si", $username, $userId);
        return mysqli_stmt_execute($stmt);
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users SET USER_NAME = ?, PASSWORD = ? WHERE USER_ID = ?"
    );

    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $username, $plainPassword, $userId);
    return mysqli_stmt_execute($stmt);
}    
    
    public static function saveProfileImage(int $userId, array $file): array
    {
        $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg'];
        $mimeType = mime_content_type($file['tmp_name']);

        if (!isset($allowedTypes[$mimeType])) {
            return [false, "Only PNG and JPG images are allowed."];
        }

        $extension = $allowedTypes[$mimeType];
        $targetDir = __DIR__ . "/../../../profile-pictures/{$userId}/";

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

    public static function usernameExists(string $username, int $excludeUserId): bool
    {
        $conn = Database::connection();
        $stmt = mysqli_prepare($conn, "SELECT USER_ID FROM users WHERE USER_NAME = ? AND USER_ID != ?");
        mysqli_stmt_bind_param($stmt, "si", $username, $excludeUserId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt) > 0;
    }
}
