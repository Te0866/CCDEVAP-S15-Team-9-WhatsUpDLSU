<?php
session_start();
require_once __DIR__ . '/controllers/CommentController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/login.html");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$searchValue = isset($_GET['search']) ? trim($_GET['search']) : '';
$eventValue = isset($_GET['event_id']) ? $_GET['event_id'] : '';

$controller = new CommentController();
$comments = $controller->listComments(
    $searchValue !== '' ? $searchValue : null,
    $eventValue !== '' ? $eventValue : null
);
$events = $controller->getEventOptions();

require __DIR__ . '/views/comments-management-view.php';
