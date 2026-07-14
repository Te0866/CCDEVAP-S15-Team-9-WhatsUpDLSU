<?php
session_start();
require_once __DIR__ . '/controllers/EventController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-dashboard.php");
    exit;
}

$eventId = isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
$redirect = isset($_POST['redirect']) && $_POST['redirect'] === 'admin-dashboard.php'
    ? 'admin-dashboard.php'
    : 'admin-event-review.php?event_id=' . $eventId;

if ($eventId > 0) {
    $controller = new EventController();
    $controller->reviewEvent($eventId, $action, $remarks);
}

header("Location: " . $redirect);
exit;
