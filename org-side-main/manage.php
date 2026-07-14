<?php
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

    $sql = "SELECT EVENT_ID, TITLE, DATE, LOCATION, VENUE, CATEGORY, APPROVAL_STATUS FROM event WHERE USER_ID = ?";
    $params = array($userId);
    $paramTypes = "i";

    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $sql = $sql . " AND TITLE LIKE ?";
        $searchTerm = "%" . $_GET['search'] . "%";
        $params[] = $searchTerm;
        $paramTypes = $paramTypes . "s";
    }

    if (isset($_GET['filter_date']) && $_GET['filter_date'] !== '') {
        $sql = $sql . " AND DATE = ?";
        $params[] = $_GET['filter_date'];
        $paramTypes = $paramTypes . "s";
    }

    if (isset($_GET['filter_category']) && $_GET['filter_category'] !== '') {
        $sql = $sql . " AND CATEGORY = ?";
        $params[] = $_GET['filter_category'];
        $paramTypes = $paramTypes . "s";
    }

    if (isset($_GET['filter_status']) && $_GET['filter_status'] !== '') {
        $sql = $sql . " AND APPROVAL_STATUS = ?";
        $params[] = $_GET['filter_status'];
        $paramTypes = $paramTypes . "s";
    }

    $sql = $sql . " ORDER BY DATE DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

    mysqli_stmt_execute($stmt);
    $eventsResult = mysqli_stmt_get_result($stmt);

    $pendingCount = 0;
    $approvedCount = 0;
    $rejectedCount = 0;

    $stmt2 = mysqli_prepare($conn, "SELECT APPROVAL_STATUS, COUNT(*) AS total FROM event WHERE USER_ID = ? GROUP BY APPROVAL_STATUS");
    mysqli_stmt_bind_param($stmt2, "i", $userId);
    mysqli_stmt_execute($stmt2);
    $countResult = mysqli_stmt_get_result($stmt2);

    while ($row = mysqli_fetch_assoc($countResult)) {
        if ($row['APPROVAL_STATUS'] === 'PENDING') {
            $pendingCount = $row['total'];
        } else if ($row['APPROVAL_STATUS'] === 'APPROVED') {
            $approvedCount = $row['total'];
        } else if ($row['APPROVAL_STATUS'] === 'REJECTED') {
            $rejectedCount = $row['total'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage Events</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/manage.css">
        <link rel="stylesheet" href="css/darkmode.css">
    </head>

    <body>
        <nav class="navbar">
            <div class="nav-left">
                <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
                <span class="logo-text"> WhatsUpDLSU </span>
            </div>

            <div class="nav-right">
                <div class="nav-links">
                    <a href="officer-dashboard.php" class="nav-tab"> Home </a>
                    <a href="create.php" class="nav-tab"> Create </a>
                    <a href="manage.php" class="nav-tab active"> Manage </a>
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

        <div class="header-row">

            <button class="back-btn" onclick="location.href='officer-dashboard.php'">
                ◀ Dashboard
            </button>

            <h1 class="page-title">Manage Events</h1>

            <div class="stats-container">

                <div class="stat-pill pending">
                    <span class="stat-number" id="pendingCount"><?php 
                                                                    echo $pendingCount; 
                                                                ?></span>
                    <span class="stat-label">Pending</span>
                </div>

                <div class="stat-pill approved">
                    <span class="stat-number" id="approvedCount"><?php 
                                                                        echo $approvedCount; 
                                                                ?></span>
                    <span class="stat-label">Approved</span>
                </div>

                <div class="stat-pill rejected">
                    <span class="stat-number" id="rejectedCount"><?php 
                                                                        echo $rejectedCount; 
                                                                 ?></span>
                    <span class="stat-label">Rejected</span>
                </div>

            </div>

        </div>

        <form class="search-section" method="GET" action="manage.php">
            <input type="text" name="search" id="searchInput" placeholder="Search Events" class="search-input" value="<?php 
                                                                                                                            echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; 
                                                                                                                        ?>">
            
            <input type="date" name="filter_date" class="filter-box" value="<?php 
                                                                                echo isset($_GET['filter_date']) ? htmlspecialchars($_GET['filter_date']) : ''; 
                                                                            ?>">

            <select name="filter_category" class="filter-box">
                <option value="">Category</option>
                <option value="ACADEMIC">Academic</option>
                <option value="CAREER">Career</option>
                <option value="NON-ACADEMIC">Non-Academic</option>
            </select>

            <select name="filter_status" class="filter-box">
                <option value="">Status</option>
                <option value="PENDING">Pending</option>
                <option value="APPROVED">Approved</option>
                <option value="REJECTED">Rejected</option>
            </select>

            <button type="submit" class="view-btn">Search</button>
            <button type="button" class="view-btn" onclick="location.href='manage.php'">Clear</button>
        </form>

        <main class="manage-container">

            <?php
                $eventCount = mysqli_num_rows($eventsResult);

                if ($eventCount === 0) {
                    echo "<p>No events yet. Go to Create to add one.</p>";
                } else {
                    while ($event = mysqli_fetch_assoc($eventsResult)) {

                        $approvalStatus = $event['APPROVAL_STATUS'];

                        if ($approvalStatus === 'PENDING') {
                            $cardClass = 'pending-card';
                            $badgeClass = 'pending';
                            $badgeText = 'Pending';
                        } else if ($approvalStatus === 'APPROVED') {
                            $cardClass = 'approved-card';
                            $badgeClass = 'approved';
                            $badgeText = 'Approved';
                        } else {
                            $cardClass = 'rejected-card';
                            $badgeClass = 'rejected';
                            $badgeText = 'Rejected';
                        }

                        $formattedDate = date("F j, Y", strtotime($event['DATE']));

                        $venueText = $event['LOCATION'];
                        if ($event['VENUE'] !== '') {
                            $venueText = $event['LOCATION'] . " - " . $event['VENUE'];
                        }
                        ?>

                        <div class="event-card <?php echo $cardClass; ?>">
                            <div class="event-content">
                                <h3><?php echo htmlspecialchars($event['TITLE']); ?></h3>
                                <p><?php echo $formattedDate; ?></p>
                                <p><?php echo htmlspecialchars($venueText); ?></p>
                            </div>
                            <div class="card-footer">
                                <span class="status-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                                <button class="view-btn" onclick="location.href='edit-event.php?event_id=<?php echo $event['EVENT_ID']; ?>'">View Details</button>
                            </div>
                        </div>

                        <?php
                    }
                }
            ?>

        </main>

        <script src="js/manage.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
