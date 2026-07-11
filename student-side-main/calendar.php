<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-side-main/login.html");
    exit;
}
$userId = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE USER_ID = ?");
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    die("User not found.");
}
require_once __DIR__ . "/../profile-picture.php";

$activeTab = 'calendar';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WhatsUpDLSU - Calendar</title>
        <link rel="stylesheet" href="css/darkmode.css">
        <link rel="stylesheet" href="css/calendar.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        
        <?php require_once __DIR__ . "/../navbar.php"; ?>

<div class="back-container">
    <button class="back-btn" onclick="window.location.href='dashboard.php'">
        Dashboard
    </button>
</div>
        <main class="calendar-layout">

            <section class="calendar-section">
                <div class="calendar-header">
    <button id="prevMonth" class="month-btn">&#10094;</button>
        <h2 id="monthTitle"></h2>
            <button id="nextMonth" class="month-btn">&#10095;</button>
    </div>
                <div class="calendar-grid" id="calendarGrid"></div>
            </section>

            <aside class="legend">
                <h3>Event Categories</h3>
                <div class="legend-item">
                    <span class="box academic"></span>
                    Academic
                </div>

                <div class="legend-item">
                    <span class="box nonacademic"></span>
                    Non-Academic
                </div>

                <div class="legend-item">
                    <span class="box career"></span>
                    Career
                </div>
            </aside>
        </main>
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>

        <h2 id="modalDate"></h2>

        <div id="modalEvents"></div>
    </div>
</div>

        <script src="js/calendar.js"></script>
        <script src="js/darkmode.js"></script>
        
    </body>
</html>
