<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    session_start();
    require_once __DIR__ . "/../dbconnection.php";

    // Make sure the officer is logged in
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
        header("Location: ../login-side-main/officer-login.html");
        exit;
    }

    // Make sure this was next sa form submission
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: create.php");
        exit;
    }

    $userId = $_SESSION['user_id'];

    $eventName = $_POST['event_name'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $room = $_POST['room'];
    $eventDate = $_POST['event_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $description = $_POST['description'];

    $category = strtoupper($category);

    // for img upload
    $bannerImage = '';

    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
        $uploadDir = __DIR__ . "/uploads/";

        // Uploads folder
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // unique img file name 
        $originalName = basename($_FILES['event_image']['name']);
        $newFileName = time() . "_" . $originalName;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetPath)) {
            $bannerImage = $newFileName;
        }
    }

    $today = date("Y-m-d");

    if ($eventDate > $today) {
        $status = 'UPCOMING';
    } else if ($eventDate === $today) {
        $status = 'ONGOING';
    } else {
        $status = 'ENDED';
    }

    // events always start as PENDING , registration open by default
    $approvalStatus = 'PENDING';
    $registrationStatus = 1;

    $stmt = mysqli_prepare($conn, "INSERT INTO event 
        (USER_ID, CATEGORY, TITLE, DESCRIPTION, LOCATION, VENUE, DATE, START_TIME, END_TIME, APPROVAL_STATUS, STATUS, REGISTRATION_STATUS, BANNER_IMAGE, CREATED_AT, UPDATED_AT) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

    mysqli_stmt_bind_param(
        $stmt, "issssssssssis", $userId, $category, $eventName, $description,
        $location, $room, $eventDate, $startTime, $endTime, $approvalStatus, $status, $registrationStatus, $bannerImage
    );

    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        header("Location: officer-dashboard.php");
        exit;
    } else {
        die("Something went wrong: " . mysqli_error($conn));
    }
?>
