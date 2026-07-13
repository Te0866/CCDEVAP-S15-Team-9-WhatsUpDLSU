<?php
    session_start();
    require_once __DIR__ . "/../dbconnection.php";

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
        header("Location: ../login-side-main/officer-login.html");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: manage.php");
        exit;
    }

    $userId = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT ORG_ID FROM users WHERE USER_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userRow = mysqli_fetch_assoc($result);
    $orgId = $userRow['ORG_ID'];

    $eventId = $_POST['event_id'];
    $eventName = $_POST['event_name'];
    $category = strtoupper($_POST['category']);
    $location = $_POST['location'];
    $room = $_POST['room'];
    $eventDate = $_POST['event_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $description = $_POST['description'];

    $today = date("Y-m-d");

    if ($eventDate > $today) {
        $status = 'UPCOMING';
    } else if ($eventDate === $today) {
        $status = 'ONGOING';
    } else {
        $status = 'ENDED';
    }

    $bannerImage = $_POST['existing_image'];

    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
        $uploadDir = __DIR__ . "/uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($_FILES['event_image']['name']);
        $newFileName = time() . "_" . $originalName;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetPath)) {
            $bannerImage = $newFileName;
        }
    }

    $stmt = mysqli_prepare($conn, "UPDATE event SET 
        CATEGORY = ?, TITLE = ?, DESCRIPTION = ?, LOCATION = ?, VENUE = ?, 
        DATE = ?, START_TIME = ?, END_TIME = ?, STATUS = ?, BANNER_IMAGE = ?, UPDATED_AT = NOW()
        WHERE EVENT_ID = ? AND ORG_ID = ?");

    mysqli_stmt_bind_param(
        $stmt, "ssssssssssii", $category, $eventName, $description, $location, $room, $eventDate, $startTime, $endTime, $status, $bannerImage, $eventId, $orgId
    );

    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        header("Location: manage.php");
        exit;
    } else {
        die("Update failed: " . mysqli_error($conn));
    }
?>