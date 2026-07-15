<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";
header("Content-Type: application/json");

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$commentId = intval($data['comment_id'] ?? 0);
$username = $_SESSION['username'];

if ($commentId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid comment id"]);
    exit;
}

$check = mysqli_prepare($conn, "SELECT USERNAME FROM comments WHERE COMMENT_ID = ?");
mysqli_stmt_bind_param($check, "i", $commentId);
mysqli_stmt_execute($check);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($check));

if (!$row) {
    echo json_encode(["success" => false, "error" => "Comment not found"]);
    exit;
}
if ($row['USERNAME'] !== $username) {
    echo json_encode(["success" => false, "error" => "You can only delete your own comments"]);
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM comments WHERE COMMENT_ID = ? AND USERNAME = ?");
mysqli_stmt_bind_param($stmt, "is", $commentId, $username);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>