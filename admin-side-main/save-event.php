<?php
session_start();
require_once __DIR__ . '/controllers/EventController.php';
require_once __DIR__ . '/../org-side-main/app/core/ImageUploader.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/login.html");
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

// Event images (reuse Organization-side uploads directory)
$uploadDir = __DIR__ . '/../org-side-main/uploads/';
$maxImages = 4;

// Keep existing images the user did not remove
$existingRaw = trim($_POST['existing_images'] ?? '');
$keptImages = [];
if ($existingRaw !== '') {
    foreach (explode(',', $existingRaw) as $name) {
        $name = trim($name);
        if ($name !== '') {
            $keptImages[] = $name;
        }
    }
}

// Upload newly selected images
$newImages = [];
if (isset($_FILES['event_images']) && is_array($_FILES['event_images']['name'])) {
    $count = count($_FILES['event_images']['name']);
    for ($i = 0; $i < $count; $i++) {
        if (count($keptImages) + count($newImages) >= $maxImages) {
            break;
        }
        $file = [
            'name' => $_FILES['event_images']['name'][$i],
            'type' => $_FILES['event_images']['type'][$i],
            'tmp_name' => $_FILES['event_images']['tmp_name'][$i],
            'error' => $_FILES['event_images']['error'][$i],
            'size' => $_FILES['event_images']['size'][$i],
        ];
        if ($file['error'] === UPLOAD_ERR_OK) {
        }
        $stored = ImageUploader::storeIfPresent($file, $uploadDir);
        if ($stored !== null) {
            $newImages[] = $stored;
        }
    }
}

$allImages = array_slice(array_merge($keptImages, $newImages), 0, $maxImages);
$fields['bannerImage'] = implode(',', $allImages);

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
