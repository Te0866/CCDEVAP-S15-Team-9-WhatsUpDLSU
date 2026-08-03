<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$eventId = intval($data['event_id'] ?? 0);
$text = trim($data['text'] ?? '');
$isAnonymous = !empty($data['is_anonymous']) ? 1 : 0;
$username = $_SESSION['username'] ?? 'Student';

if ($eventId <= 0 || $text === '') {
    echo json_encode(["success" => false, "error" => "Missing or invalid fields"]);
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO comments (EVENT_ID, USERNAME, TEXT, IS_ANONYMOUS) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "issi", $eventId, $username, $text, $isAnonymous);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        "success" => true,
        "author" => $isAnonymous ? "Anonymous" : $username,
        "text" => $text
    ]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
