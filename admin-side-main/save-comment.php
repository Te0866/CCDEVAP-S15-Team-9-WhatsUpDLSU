<?php
session_start();
require_once __DIR__ . '/controllers/CommentController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: comments-management.php");
    exit;
}

$commentId = $_POST['comment_id'] ?? null;
$eventId = $_POST['event_id'] ?? '';
$username = $_POST['username'] ?? '';
$text = $_POST['text'] ?? '';
$isAnonymous = isset($_POST['is_anonymous']);

$controller = new CommentController();
$result = $controller->saveComment($commentId, $eventId, $username, $text, $isAnonymous);

if (!$result['success']) {
    $_SESSION['add_comment_error'] = $result['error'];
    $redirect = $commentId ? "add-comment.php?id=" . urlencode($commentId) : "add-comment.php";
    header("Location: " . $redirect);
    exit;
}

$_SESSION['success_message'] = $commentId
    ? "Comment updated successfully!"
    : "Comment created successfully!";

header("Location: add-comment.php" . ($commentId ? "?id=" . urlencode($commentId) : ""));
exit;
