<?php
echo "TEST123";
exit;
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

        <nav class="navbar">

            <div class="nav-left">
                <a href="dashboard.php" class="nav-left">
                    <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
                    <span class="logo-text">WhatsUpDLSU</span>
                </a>
            </div>

            <div class="nav-right">

                <div class="nav-links">
                    <a href="dashboard.php" class="nav-tab active">Home</a>
                    <a href="events.php" class="nav-tab">Events</a>
                    <a href="calendar.php" class="nav-tab">Calendar</a>
                </div>

                <div class="profile-section">
                    <button class="profile-btn" id="profileBtn">
                        <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile" class="profile-pic">
                    </button>

                    <div class="dropdown-menu" id="dropdownMenu">
                        <button type="button" class="dark-mode-btn"> DARK/LIGHT MODE </button>
                        <button onclick="window.location.href='edit-profile.php'"> EDIT USER DETAILS </button>
                        <button onclick="window.location.href='../login-side-main/login.html'"> LOG OUT </button>
                    </div>
                </div>
            </div>
        </nav>

       <main class="dashboard-layout">

    <section class="banner">
        <h1>Hi Username, Discover What's Happening at DLSU</h1>
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
            Academic
        </div>

        <div class="category-item" onclick="location.href='events.php?category=Non-Academic'">
            <span class="color nonacademic"></span>
            Non-academic
        </div>

        <div class="category-item" onclick="location.href='events.php?category=Career'">
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
