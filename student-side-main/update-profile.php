<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

// --- DEBUG ---
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("Received username: " . $username);
error_log("Received password: " . $password);
// --- END DEBUG ---

if ($username === '' || $password === '') {
    echo json_encode(["success" => false, "error" => "Missing fields"]);
    exit;
}

$userId = $_SESSION['user_id'];

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

if (mysqli_stmt_execute($stmt)) {
    error_log("Rows affected: " . mysqli_stmt_affected_rows($stmt));
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
