<?php
session_start();
require_once __DIR__ . '/controllers/CommentController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/login.html");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$commentId = isset($_GET['id']) && $_GET['id'] !== '' ? (int) $_GET['id'] : null;

$controller = new CommentController();
$formData = $controller->getCommentFormData($commentId);

$mode = $formData['mode'];
$commentId = $formData['commentId'];
$eventId = $formData['eventId'];
$username = $formData['username'];
$text = $formData['text'];
$isAnonymous = $formData['isAnonymous'];
$events = $formData['events'];

$errorMessage = isset($_SESSION['add_comment_error']) ? $_SESSION['add_comment_error'] : null;
unset($_SESSION['add_comment_error']);

require __DIR__ . '/views/add-comment-view.php';
