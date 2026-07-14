<?php
    session_start();
    header('Content-Type: application/json');

    require_once __DIR__ . "/../dbconnection.php";

    function respond($data) {
        echo json_encode($data);
        exit;
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
        respond(["success" => false, "error" => "Not logged in."]);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond(["success" => false, "error" => "Invalid request method."]);
    }

    $userId = $_SESSION['user_id'];

    $orgName = trim($_POST['orgName'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($orgName === '' || $password === '') {
        respond(["success" => false, "error" => "Organization name and password are required."]);
    }

    // The organization name doubles as this officer's login username, so it
    // must be unique across the whole users table (not just other officers).
    $stmt = mysqli_prepare($conn, "SELECT USER_ID FROM users WHERE USER_NAME = ? AND USER_ID != ?");
    mysqli_stmt_bind_param($stmt, "si", $orgName, $userId);
    mysqli_stmt_execute($stmt);
    $existing = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($existing) > 0) {
        respond(["success" => false, "error" => "That organization name is already taken. Please choose another."]);
    }

    // Optional profile picture upload, same convention as the student side's
    // profile-pictures/{USER_ID}/pfp.{png|jpg}.
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg'];
        $mimeType = mime_content_type($_FILES['profileImage']['tmp_name']);

        if (!isset($allowedTypes[$mimeType])) {
            respond(["success" => false, "error" => "Only PNG and JPG images are allowed."]);
        }

        $extension = $allowedTypes[$mimeType];
        $targetDir = __DIR__ . "/../profile-pictures/{$userId}/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        foreach (['png', 'jpg'] as $ext) {
            $existingFile = $targetDir . "pfp.{$ext}";
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }

        $targetPath = $targetDir . "pfp.{$extension}";

        if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetPath)) {
            respond(["success" => false, "error" => "Failed to save uploaded image."]);
        }
    }

    $stmt = mysqli_prepare($conn, "UPDATE users SET USER_NAME = ?, PASSWORD = ? WHERE USER_ID = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $orgName, $password, $userId);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        // Keep the session in sync so the rest of the app reflects the change
        // immediately without requiring a fresh login.
        $_SESSION['username'] = $orgName;
        respond(["success" => true]);
    } else {
        respond(["success" => false, "error" => "Update failed: " . mysqli_error($conn)]);
    }
?>
