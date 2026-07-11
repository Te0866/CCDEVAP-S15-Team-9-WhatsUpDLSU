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

$activeTab = 'home';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WhatsUpDLSU Dashboard</title>

        <link rel="stylesheet" href="css/dashboard.css">
        <link rel="stylesheet" href="css/darkmode.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>

        <?php require_once __DIR__ . "/../navbar.php"; ?>

        <main class="dashboard-layout">

    <section class="banner">
        <h1>Hi <?php echo htmlspecialchars($user['USER_NAME']); ?>, Discover What's Happening at DLSU</h1>
        <p>
            Stay updated with university events, organization activities,
            workshops, seminars, and campus announcements.
        </p>
    </section>

    <section class="chart-container pie-chart">
        <h2 class="chart-title">Distribution of Event Categories</h2>
        <canvas id="studentChart"></canvas>
    </section>

    <section class="chart-container bar-chart">
        <h2 class="chart-title">Most Popular Events</h2>
        <canvas id="popularChart"></canvas>
    </section>


    <section class="category-box">
        <h2>Browse by Category</h2>

        <div class="category-item"
         onclick="location.href='events.php?category=ACADEMIC'">
            <span class="color academic"></span>
                Academic
        </div>

        <div class="category-item"
         onclick="location.href='events.php?category=NON-ACADEMIC'">
            <span class="color nonacademic"></span>
                Non-academic
        </div>

        <div class="category-item"
         onclick="location.href='events.php?category=CAREER'">
            <span class="color career"></span>
                Career
    </div>
    </section>
           <section class="carousel-section">
        <h2>Interested Events!</h2>

        <div class="carousel">
            <button class="arrow">&lt;</button>

            <div id="interestedEventsContainer">
                <div class="event-card">
                    <h3>No Events Yet</h3>
                    <p>Add events from the Events page</p>
                </div>
            </div>

            <button class="arrow">&gt;</button>
        </div>
    </section>

</main>
        <script src="js/dashboard.js"></script>
        <script src="js/darkmode.js"></script>
    </body>
</html>
