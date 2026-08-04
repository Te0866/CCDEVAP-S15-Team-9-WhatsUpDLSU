<?php
session_start();
require_once __DIR__ . '/controllers/EventController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-dashboard.php");
    exit;
}

$eventId = $_POST['event_id'] ?? '';

if ($eventId !== '') {
    $controller = new EventController();
    $success = $controller->deleteEvent($eventId);

    if ($success) {
        $_SESSION['success_message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete event.";
    }
}

header("Location: admin-dashboard.php");
exit;
