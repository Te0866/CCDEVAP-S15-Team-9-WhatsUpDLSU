<?php
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

        <?php require __DIR__ . "/partials/navbar.view.php"; ?>

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