
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

    // Get org's ORG_ID
    $stmt = mysqli_prepare($conn, "SELECT ORG_ID FROM users WHERE USER_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userRow = mysqli_fetch_assoc($result);
    $orgId = $userRow['ORG_ID'];

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
        (ORG_ID, CATEGORY, TITLE, DESCRIPTION, LOCATION, VENUE, DATE, START_TIME, END_TIME, APPROVAL_STATUS, STATUS, REGISTRATION_STATUS, BANNER_IMAGE, CREATED_AT, UPDATED_AT) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

    mysqli_stmt_bind_param(
        $stmt, "isssssssssiss", $orgId, $category, $eventName, $description,
        $location, $room, $eventDate, $startTime, $endTime, $approvalStatus, $status, $registrationStatus, $bannerImage
    );


   echo "<pre>";
echo "orgId = "; var_dump($orgId);
echo "category = "; var_dump($category);
echo "eventName = "; var_dump($eventName);
echo "description = "; var_dump($description);
echo "location = "; var_dump($location);
echo "room = "; var_dump($room);
echo "eventDate = "; var_dump($eventDate);
echo "startTime = "; var_dump($startTime);
echo "endTime = "; var_dump($endTime);
echo "approvalStatus = "; var_dump($approvalStatus);
echo "status = "; var_dump($status);
echo "registrationStatus = "; var_dump($registrationStatus);
echo "bannerImage = "; var_dump($bannerImage);
exit;

    if ($success) {
        header("Location: officer-dashboard.php");
        exit;
    } else {
        die("Something went wrong: " . mysqli_error($conn));
    }
?>
