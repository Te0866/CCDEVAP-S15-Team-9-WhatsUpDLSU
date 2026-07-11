<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$userId = $_SESSION['user_id'];

if ($username === '' || $password === '') {
    echo json_encode(["success" => false, "error" => "Missing fields"]);
    exit;
}

// Handle image upload if one was provided
if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg'];
    $mimeType = mime_content_type($_FILES['profileImage']['tmp_name']);

    if (!isset($allowedTypes[$mimeType])) {
        echo json_encode(["success" => false, "error" => "Only PNG and JPG images are allowed."]);
        exit;
    }

    $extension = $allowedTypes[$mimeType];
    $targetDir = __DIR__ . "/../profile-pictures/{$userId}/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Remove any existing pfp (regardless of prior extension) to avoid stale duplicates
    foreach (['png', 'jpg'] as $ext) {
        $existing = $targetDir . "pfp.{$ext}";
        if (file_exists($existing)) {
            unlink($existing);
        }
    }

    $targetPath = $targetDir . "pfp.{$extension}";
    if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetPath)) {
        echo json_encode(["success" => false, "error" => "Failed to save uploaded image."]);
        exit;
    }
}

$stmt = mysqli_prepare($conn, "UPDATE users SET USER_NAME = ?, PASSWORD = ? WHERE USER_ID = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "ssi", $username, $password, $userId);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
