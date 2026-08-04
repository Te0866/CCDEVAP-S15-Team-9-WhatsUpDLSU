<?php
session_start();
require_once __DIR__ . '/controllers/CommentController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: comments-management.php");
    exit;
}

$commentId = $_POST['comment_id'] ?? '';

if ($commentId !== '') {
    $controller = new CommentController();
    $success = $controller->deleteComment($commentId);

    if ($success) {
        $_SESSION['success_message'] = "Comment deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete comment.";
    }
}

header("Location: comments-management.php");
exit;
