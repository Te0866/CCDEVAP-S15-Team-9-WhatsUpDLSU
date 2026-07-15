<?php

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

        <?php require __DIR__ . "/partials/navbar.view.php"; ?>

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

                <div class="category-item" onclick="location.href='events.php?category=Academic'">
                    <span class="color academic"></span>
                    Academic Events
                </div>

                <div class="category-item" onclick="location.href='events.php?category=Non-academic'">
                    <span class="color nonacademic"></span>
                    Non-academic Events
                </div>

                <div class="category-item" onclick="location.href='events.php?category=Career'">
                    <span class="color career"></span>
                    Career Events
                </div>
            </section>

            <section class="carousel-section">
                <h2>Interested Events!</h2>

                <div class="carousel">
                    <button id="prevBtn" class="arrow">&lt;</button>

                    <div id="interestedEventsContainer">
                        <div class="event-card">
                            <h3>No Events Yet</h3>
                            <p>Add events from the Events page</p>
                        </div>
                    </div>

                    <button id="nextBtn" class="arrow">&gt;</button>
                </div>
            </section>

        </main>
        <script src="js/dashboard.js"></script>
        <script src="js/darkmode.js"></script>
    </body>
</html>
