<?php

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WhatsUpDLSU Dashboard</title>

<link rel="stylesheet" href="../assets/styles/student/darkmode.css">
<link rel="stylesheet" href="../assets/styles/student/dashboard.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body class="s-student-dashboard s-student-darkmode">

        <?php require __DIR__ . "/partials/navbar.view.php"; ?>

        <main class="dashboard-layout">

            <section class="banner">
                <h1>Hi <?php echo htmlspecialchars($user['USER_NAME']); ?>, Discover What's Happening at DLSU</h1>
                <p>
                    Stay updated with university events, organization activities,
                    workshops, seminars, and campus announcements.
                </p>
            </section>

            <div class="charts-row">
    <section class="chart-container pie-chart">
        <h2 class="chart-title">Distribution of Event Categories</h2>
        <canvas id="studentChart"></canvas>
    </section>
    <section class="chart-container bar-chart">
        <h2 class="chart-title">Most Popular Events</h2>
        <canvas id="popularChart"></canvas>
    </section>
    <section class="chart-container pie-chart">
        <h2 class="chart-title">My Interests by Category</h2>
        <canvas id="myInterestsChart"></canvas>
    </section>
</div>
            
            <div class="bottom-row">

                <section class="category-box">
                    <h2>Browse by Category</h2>

                    <div class="category-list" id="categoryList">
                        <a class="category-item" href="events.php?category=Academic">
                            <span class="category-left">
                                <span class="color academic"></span>
                                Academic Events
                            </span>
                            <span class="category-count" id="countAcademic">0</span>
                        </a>

                        <a class="category-item" href="events.php?category=Non-academic">
                            <span class="category-left">
                                <span class="color nonacademic"></span>
                                Non-academic Events
                            </span>
                            <span class="category-count" id="countNonAcademic">0</span>
                        </a>

                        <a class="category-item" href="events.php?category=Career">
                            <span class="category-left">
                                <span class="color career"></span>
                                Career Events
                            </span>
                            <span class="category-count" id="countCareer">0</span>
                        </a>

                        <a class="category-item category-item-all" href="events.php">
                            <span class="category-left">All events</span>
                            <span class="category-arrow">&rarr;</span>
                        </a>
                    </div>
                </section>

                <section class="carousel-section">
                    <div class="carousel-header">
                        <h2>Interested Events</h2>

                        <div class="carousel-controls">
                            <button id="prevBtn" class="carousel-arrow" aria-label="Previous events">&#8249;</button>
                            <button id="nextBtn" class="carousel-arrow" aria-label="Next events">&#8250;</button>
                        </div>
                    </div>

                    <div class="event-grid" id="interestedEventsContainer">
                        <div class="event-card event-card-empty">
                            <h3>No Events Yet</h3>
                            <p>Add events from the Events page</p>
                        </div>
                    </div>
                </section>

            </div>

        </main>
<script src="../assets/scripts/student/darkmode.js"></script>
<script src="../assets/scripts/student/colors.js"></script>
<script src="../assets/scripts/student/dashboard.js"></script>
    </body>
</html>
