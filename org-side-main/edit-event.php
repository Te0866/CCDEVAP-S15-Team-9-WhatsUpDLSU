<?php
    session_start();
    require_once __DIR__ . "/../dbconnection.php";

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
        header("Location: ../login-side-main/officer-login.html");
        exit;
    }

    $userId = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT u.*, o.ORG_NAME FROM users u LEFT JOIN organizations o ON u.ORG_ID = o.ORG_ID WHERE u.USER_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        die("User not found.");
    }

    $orgId = $user['ORG_ID'];

    if (!isset($_GET['event_id'])) {
        header("Location: manage.php");
        exit;
    }

    $eventId = $_GET['event_id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM event WHERE EVENT_ID = ? AND ORG_ID = ?");
    mysqli_stmt_bind_param($stmt, "ii", $eventId, $orgId);
    mysqli_stmt_execute($stmt);
    $eventResult = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($eventResult);

    if (!$event) {
        die("Event not found or you don't have permission to edit it.");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/edit-event.css">
    <link rel="stylesheet" href="css/darkmode.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-left">
            <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
            <span class="logo-text">WhatsUpDLSU</span>
        </div>

        <div class="nav-right">

            <div class="nav-links">
                <a href="officer-dashboard.php" class="nav-tab">Home</a>
                <a href="create.php" class="nav-tab">Create</a>
                <a href="manage.php" class="nav-tab active">Manage</a>
            </div>

            <div class="profile-section">
                <button class="profile-btn" id="profileBtn">
                    <?php 
                        echo htmlspecialchars($user['ORG_NAME']); 
                    ?> ▼
                </button>

                <div class="dropdown-menu" id="dropdownMenu">
                    <button onclick="location.href='edit-organization.php'">
                        EDIT ORGANIZATION DETAILS
                    </button>

                    <button class="dark-mode-btn">
                        DARK/LIGHT MODE
                    </button>

                    <button onclick="window.location.href='../login-side-main/officer-login.html'">
                        LOG OUT
                    </button>
                </div>
            </div>

        </div>
    </nav>

    <main class="edit-page">

        <div class="header-row">

            <button class="back-btn" onclick="location.href='manage.php'">
                ◀ Manage
            </button>

            <h1 class="page-title">
                Edit Event
            </h1>

            <div class="header-spacer"></div>

        </div>

        <form class="form-card" action="update-event-process.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="event_id" value="<?php 
                                                            echo $event['EVENT_ID']; 
                                                        ?>">
            <input type="hidden" name="existing_image" value="<?php 
                                                                    echo htmlspecialchars($event['BANNER_IMAGE']); 
                                                              ?>">

            <div class="form-grid">

                <div class="form-left">

                    <h3 class="section-heading">Event Details</h3>

                    <div class="form-group">
                        <label>Event Name <span class="required-badge">required</span></label>
                        <input type="text" name="event_name" value="<?php 
                                                                        echo htmlspecialchars($event['TITLE']); 
                                                                    ?>">
                    </div>

                    <div class="form-group">
                        <label>Category <span class="required-badge">required</span></label>

                        <select name="category">
                            <?php
                                $categories = array("ACADEMIC", "NON-ACADEMIC", "CAREER");
                                $categoryLabels = array("ACADEMIC" => "Academic", "NON-ACADEMIC" => "Non-Academic", "CAREER" => "Career");

                                foreach ($categories as $categoryOption) {
                                    $selected = "";
                                    if ($event['CATEGORY'] === $categoryOption) {
                                        $selected = "selected";
                                    }
                                    echo "<option value=\"" . $categoryOption . "\" " . $selected . ">" . $categoryLabels[$categoryOption] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <h3 class="section-heading">Where is this happening?</h3>

                    <div class="form-group">
                        <label>Location <span class="required-badge">required</span></label>

                        <select name="location">
                            <?php
                                $locations = array(
                                    "Andrew Gonzalez Hall (AG)", "Br. Connon Hall (CONNON)", "Br. Andrew Gonzalez FSC Sports Complex",
                                    "Br. Miguel Hall (MIGUEL)", "Enrique M. Razon Sports Center", "Faculty Center (FACULTY)",
                                    "Gokongwei Hall (GOKONGWEI)", "Henry Sy Sr. Hall (HSSH)", "John Gokongwei Hall (JGH)",
                                    "LS Building (LS)", "Mutien Marie Hall", "St. Joseph Hall (SJ)",
                                    "St. La Salle Hall (LS)", "STRC Building", "William Hall (WILLIAM)",
                                    "Yuchengco Hall (YUCH)", "Online"
                                );

                                foreach ($locations as $locationOption) {
                                    $selected = "";
                                    if ($event['LOCATION'] === $locationOption) {
                                        $selected = "selected";
                                    }
                                    echo "<option " . $selected . ">" . $locationOption . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Room / Venue <span class="optional-badge">optional</span></label>
                        <input type="text" name="room" value="<?php 
                                                                    echo htmlspecialchars($event['VENUE']); 
                                                                ?>">
                    </div>

                    <h3 class="section-heading">When is this happening?</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date <span class="required-badge">required</span></label>
                            <input type="date" name="event_date" value="<?php 
                                                                            echo $event['DATE']; 
                                                                        ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Time <span class="required-badge">required</span></label>
                            <input type="time" name="start_time" value="<?php 
                                                                            echo substr($event['START_TIME'], 0, 5); 
                                                                        ?>">
                        </div>

                        <div class="form-group">
                            <label>End Time <span class="required-badge">required</span></label>
                            <input type="time" name="end_time" value="<?php 
                                                                            echo substr($event['END_TIME'], 0, 5); 
                                                                        ?>">
                        </div>
                    </div>

                </div>

                <div class="form-right">

                    <div class="form-group">
                        <label>Event Poster / Image <span class="optional-badge">optional</span></label>

                        <div class="upload-box">
                            <?php if ($event['BANNER_IMAGE'] !== '') { ?>
                                <div class="file-chip">
                                    <?php echo htmlspecialchars($event['BANNER_IMAGE']); ?>
                                </div>
                            <?php } ?>

                            <div class="upload-icon">📁</div>
                            <p>Click to upload or drag and drop</p>
                            <input type="file" id="eventImage" name="event_image">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="required-badge">required</span></label>
                        <textarea name="description" rows="8"><?php 
                                                                    echo htmlspecialchars($event['DESCRIPTION']); 
                                                              ?></textarea>
                    </div>

                </div>

            </div>

            <div class="button-group">
                <button type="button" class="delete-btn" id="deleteBtn">Delete Event</button>
                <button type="submit" class="submit-btn" id="submitBtn">Update Event</button>
            </div>

        </form>

    </main>

    <script src="js/edit-event.js"></script>
    <script src="js/darkmode.js"></script>

</body>

</html>
