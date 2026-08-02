<?php
session_start();
require_once __DIR__ . '/controllers/EventController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$eventId = isset($_GET['id']) && $_GET['id'] !== '' ? (int) $_GET['id'] : null;

$controller = new EventController();
$formData = $controller->getEventFormData($eventId);

$mode = $formData['mode'];
$eventId = $formData['eventId'];
$title = $formData['title'];
$category = $formData['category'];
$description = $formData['description'];
$location = $formData['location'];
$venue = $formData['venue'];
$date = $formData['date'];
$startTime = $formData['startTime'];
$endTime = $formData['endTime'];
$approvalStatus = $formData['approvalStatus'];
$remarks = $formData['remarks'];
$registrationStatus = $formData['registrationStatus'];
$userId = $formData['userId'];
$organizers = $formData['organizers'];

$errorMessage = isset($_SESSION['add_event_error']) ? $_SESSION['add_event_error'] : null;
unset($_SESSION['add_event_error']);

require __DIR__ . '/views/add-event-view.php';
