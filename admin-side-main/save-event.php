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

$eventId = $_POST['event_id'] ?? null;

$fields = [
    'title' => $_POST['title'] ?? '',
    'category' => $_POST['category'] ?? '',
    'description' => $_POST['description'] ?? '',
    'location' => $_POST['location'] ?? '',
    'venue' => $_POST['venue'] ?? '',
    'date' => $_POST['date'] ?? '',
    'startTime' => $_POST['start_time'] ?? '',
    'endTime' => $_POST['end_time'] ?? '',
    'approvalStatus' => $_POST['approval_status'] ?? 'PENDING',
    'remarks' => $_POST['remarks'] ?? '',
    'registrationStatus' => isset($_POST['registration_status']) ? 1 : 0,
    'userId' => $_POST['user_id'] ?? 0,
];

$controller = new EventController();
$result = $controller->saveEvent($eventId, $fields);

if (!$result['success']) {
    $_SESSION['add_event_error'] = $result['error'];
    $redirect = $eventId ? "add-event.php?id=" . urlencode($eventId) : "add-event.php";
    header("Location: " . $redirect);
    exit;
}

$_SESSION['success_message'] = $eventId
    ? "Event updated successfully!"
    : "Event created successfully!";

header("Location: add-event.php" . ($eventId ? "?id=" . urlencode($eventId) : ""));
exit;
