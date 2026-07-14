<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
        session_start();

    require_once __DIR__ . "/../dbconnection.php";

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
        header("Location: ../login-side-main/officer-login.html");
        exit;
    }

    $userId = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE USER_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        die("User not found.");
    }

    require_once __DIR__ . "/../profile-picture.php";

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND STATUS IN ('ONGOING', 'UPCOMING') AND APPROVAL_STATUS = 'APPROVED'");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $activeCount = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND APPROVAL_STATUS = 'PENDING'");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $pendingCount = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND STATUS = 'ENDED'");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $pastCount = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

    $stmt = mysqli_prepare($conn, "SELECT EVENT_ID, TITLE, DATE, CATEGORY FROM event WHERE USER_ID = ? AND APPROVAL_STATUS = 'APPROVED' AND STATUS IN ('UPCOMING', 'ONGOING') ORDER BY DATE ASC LIMIT 10");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $eventsResult = mysqli_stmt_get_result($stmt);

    $stmt = mysqli_prepare($conn, "SELECT EVENT_ID, TITLE, APPROVAL_STATUS, REMARKS, UPDATED_AT FROM event WHERE USER_ID = ? ORDER BY UPDATED_AT DESC LIMIT 5");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $activityResult = mysqli_stmt_get_result($stmt);

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND CATEGORY = 'ACADEMIC'");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $academicCount = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND CATEGORY = 'NON-ACADEMIC'");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $nonAcademicCount = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND CATEGORY = 'CAREER'");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $careerCount = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsUpDLSU - Officer Dashboard</title>
    <link rel="stylesheet" href="css/officer-dashboard.css">
    <link rel="stylesheet" href="css/darkmode.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
            <span class="logo-text"> WhatsUpDLSU </span>
        </div>

        <div class="nav-right">
            <div class="nav-links">
                <a href="officer-dashboard.php" class="nav-tab active"> Home </a>
                <a href="create.php" class="nav-tab"> Create </a>
                <a href="manage.php" class="nav-tab"> Manage </a>
            </div>

            <div class="profile-section">
                <button class="profile-btn" id="profileBtn">
                    <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile" class="profile-pic">
                </button>

                <div class="dropdown-menu" id="dropdownMenu">
                    <button onclick="location.href='edit-organization.php'"> EDIT ORGANIZATION DETAILS </button>
                    <button class="dark-mode-btn"> DARK/LIGHT MODE </button>
                    <button onclick="window.location.href='../login-side-main/login.html'"> LOG OUT </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="dashboard">

        <section class="banner">
            <div class="banner-text">
                <h1> Welcome, <?php 
                                    echo htmlspecialchars($user['USER_NAME']); 
                                ?>! </h1>
                <p> Manage your events and track submissions. </p>
            </div>
            <button class="create-btn" onclick="location.href='create.php'"> + Create event </button>
        </section>

        <section class="stats-row">
            <div class="stat-card">
                <span class="stat-label"> Active events </span>
                <span class="stat-value" id="statActive"> <?php echo $activeCount; ?> </span>
            </div>

            <div class="stat-card">
                <span class="stat-label"> Pending approval </span>
                <span class="stat-value" id="statPending"> <?php echo $pendingCount; ?> </span>
            </div>

            <div class="stat-card">
                <span class="stat-label"> Past events </span>
                <span class="stat-value" id="statPast"> <?php echo $pastCount; ?> </span>
            </div>
        </section>

        <div class="dashboard-grid">

            <div class="left-column">
                <section class="events-container">
                    <div class="events-header">
                        <h2> Events </h2>

                        <div class="carousel-controls">
                            <button class="manage-events-btn" onclick="location.href='manage.php'">Manage Events</button>
                            <button class="carousel-arrow" id="eventsPrevBtn" aria-label="Previous events" onclick="scrollEvents(-1)">&#8249;</button>
                            <button class="carousel-arrow" id="eventsNextBtn" aria-label="Next events" onclick="scrollEvents(1)">&#8250;</button>
                        </div>
                    </div>

                    <div class="event-grid" id="eventGrid">
                        <?php
                            $eventCount = mysqli_num_rows($eventsResult);

                            if ($eventCount === 0) {
                                echo "<p>No events yet. Click \"+ Create event\" to add one.</p>";
                            } else {
                                while ($event = mysqli_fetch_assoc($eventsResult)) {
                                    
                                    $category = strtolower($event['CATEGORY']);
                                    
                                    if ($category === 'non-academic') {
                                        $categoryClass = 'tag-nonacademic';
                                    } else if ($category === 'academic') {
                                        $categoryClass = 'tag-academic';
                                    } else if ($category === 'career') {
                                        $categoryClass = 'tag-career';
                                    } else {
                                        $categoryClass = 'tag-default';
                                    }

                                    $formattedDate = date("M j, Y", strtotime($event['DATE']));
                                    ?>

                                    <div class="event-card">
                                        <h3> <?php echo htmlspecialchars($event['TITLE']); ?> </h3>
                                        <p> <?php echo $formattedDate; ?> </p>
                                        <span class="tag <?php echo $categoryClass; ?>"> <?php echo htmlspecialchars($event['CATEGORY']); ?> </span>
                                    </div>

                                    <?php
                                }
                            }
                        ?>
                    </div>
                </section>

                <section class="activity-box">
                    <h2> Recent activity </h2>

                    <ul class="activity-list">
                        <?php
                            $activityCount = mysqli_num_rows($activityResult);

                            if ($activityCount === 0) {
                                echo "<p>No recent activity yet.</p>";
                            } else {
                                while ($activity = mysqli_fetch_assoc($activityResult)) {

                                    if ($activity['APPROVAL_STATUS'] === 'APPROVED') {
                                        $iconClass = 'icon-approved';
                                        $iconSymbol = '✓';
                                        $statusText = 'was approved';
                                    } else if ($activity['APPROVAL_STATUS'] === 'PENDING') {
                                        $iconClass = 'icon-pending';
                                        $iconSymbol = '⏱';
                                        $statusText = 'submitted for review';
                                    } else if ($activity['APPROVAL_STATUS'] === 'REJECTED') {
                                        $iconClass = 'icon-rejected';
                                        $iconSymbol = '✕';
                                        $statusText = 'was rejected';
                                    } else {
                                        $iconClass = 'icon-pending';
                                        $iconSymbol = '•';
                                        $statusText = 'was updated';
                                    }

                                    $updatedTimestamp = strtotime($activity['UPDATED_AT']);
                                    $nowTimestamp = time();
                                    $secondsAgo = $nowTimestamp - $updatedTimestamp;
                                    $daysAgo = floor($secondsAgo / 86400);

                                        if ($daysAgo <= 0) {
                                            $timeText = 'Today';
                                        } else if ($daysAgo === 1) {
                                            $timeText = '1 day ago';
                                        } else {
                                            $timeText = $daysAgo . ' days ago';
                                        }
                                    ?>

                                    <li class="activity-item">
                                        <span class="activity-icon <?php echo $iconClass; ?>"><?php echo $iconSymbol; ?></span>
                                        <div class="activity-text">
                                            <p> <strong> <?php echo htmlspecialchars($activity['TITLE']); ?> </strong> <?php echo $statusText; ?> </p>
                                            <span class="activity-time"> <?php echo $timeText; ?> </span>
                                        </div>
                                        <?php
                                            $hasRemarks = ($activity['APPROVAL_STATUS'] === 'APPROVED' || $activity['APPROVAL_STATUS'] === 'REJECTED')
                                                && trim($activity['REMARKS'] ?? '') !== '';

                                            if ($hasRemarks) {
                                        ?>
                                        <button
                                            type="button"
                                            class="view-remarks-btn"
                                            data-title="<?php echo htmlspecialchars($activity['TITLE']); ?>"
                                            data-status="<?php echo htmlspecialchars($activity['APPROVAL_STATUS']); ?>"
                                            data-remarks="<?php echo htmlspecialchars($activity['REMARKS'] ?? ''); ?>"
                                        >
                                            View Remarks
                                        </button>
                                        <?php
                                            }
                                        ?>
                                    </li>

                                    <?php
                                }
                            }
                        ?>
                    </ul>

                </section>
            </div>

            <div class="right-column">
                <section class="chart-box">
                    <h2> Event status overview </h2>
                    <div class="chart-wrap">
                        <canvas id="statusChart"></canvas>
                    </div>

                    <div class="legend">
                        <span class="legend-item"><span class="color-box blue"></span> Active</span>
                        <span class="legend-item"><span class="color-box purple"></span> Pending</span>
                        <span class="legend-item"><span class="color-box yellow"></span> Past</span>
                    </div>
                </section>

                <section class="chart-box">
                    <h2> Events by category </h2>
                    <div class="chart-wrap">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </section>
            </div>

        </div>
    </main>

    <div class="modal-overlay" id="remarksModalOverlay">
        <div class="modal-box">
            <button type="button" class="modal-close-btn" id="remarksModalClose">&times;</button>
            <h3 id="remarksModalTitle">Event Title</h3>
            <span class="modal-status" id="remarksModalStatus"></span>
            <p class="modal-remarks-label">Admin Remarks</p>
            <p id="remarksModalText" class="modal-remarks-text"></p>
        </div>
    </div>

    <script>
        const activeCount = <?php echo $activeCount; ?>;
        const pendingCount = <?php echo $pendingCount; ?>;
        const pastCount = <?php echo $pastCount; ?>;

        const academicCount = <?php echo $academicCount; ?>;
        const nonAcademicCount = <?php echo $nonAcademicCount; ?>;
        const careerCount = <?php echo $careerCount; ?>;
    </script>

    <script src="js/officer-dashboard.js"></script>
    <script src="js/darkmode.js"></script>

</body>
</html>
