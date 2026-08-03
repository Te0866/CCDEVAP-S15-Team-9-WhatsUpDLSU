<?php
session_start();
require_once __DIR__ . '/controllers/EventController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

if (!isset($_GET['event_id']) || $_GET['event_id'] === '') {
    header("Location: admin-dashboard.php");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$eventId = (int) $_GET['event_id'];

$controller = new EventController();
$event = $controller->getEventForReview($eventId);

if (!$event) {
    header("Location: admin-dashboard.php");
    exit;
}

require __DIR__ . '/views/admin-event-review-view.php';
