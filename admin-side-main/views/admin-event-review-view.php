<?php
$duration = date("g:i A", strtotime($event['START_TIME'])) . " - " . date("g:i A", strtotime($event['END_TIME']));
$formattedDate = date("F j, Y", strtotime($event['DATE']));
$category = ucwords(strtolower(str_replace('-', ' ', $event['CATEGORY'])));
$statusLabel = ucfirst(strtolower($event['STATUS']));
$registrationLabel = $event['REGISTRATION_STATUS'] ? 'Open' : 'Closed';
$bannerImage = !empty($event['BANNER_IMAGE']) ? '../org-side-main/uploads/' . $event['BANNER_IMAGE'] : '';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Review</title>

        <link rel="stylesheet" href="css/admin-event-review.css">
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
                    <a href="admin-dashboard.php" class="nav-tab active"> Manage Events </a>
                    <a href="account-management.php" class="nav-tab"> Account Management </a>
                </div>

                <div class="profile-section">
                    <button class="profile-btn" id="profileBtn"> <?php echo htmlspecialchars($adminName); ?> ▼ </button>

                    <div class="dropdown-menu" id="dropdownMenu">
                        <button class="dark-mode-btn"> DARK/LIGHT MODE </button>
                        <button onclick="window.location.href='../login-side-main/logout.php'"> LOG OUT </button>
                    </div>
                </div>
            </div>
        </nav>


        <main class="review-page">
            <form class="action-panel" method="POST" action="update-event-status.php">
                <input type="hidden" name="event_id" value="<?php echo (int) $event['EVENT_ID']; ?>">
                <input type="hidden" name="redirect" value="admin-dashboard.php">

                <button class="back-btn" type="button" onclick="location.href='admin-dashboard.php'"> Back </button>

                <div class="remarks-section">
                    <h3>Remarks</h3>
                    <textarea name="remarks" placeholder="Enter remarks here..."><?php echo htmlspecialchars($event['REMARKS'] ?? ''); ?></textarea>
                </div>

                <button class="approve-btn" id="approveBtn" type="submit" name="action" value="approve"> Approve </button>
                <button class="reject-btn" id="rejectBtn" type="submit" name="action" value="reject"> Reject </button>
            </form>

            <section class="event-panel">
                <div class="event-info">
                    <h1><?php echo htmlspecialchars($event['TITLE']); ?></h1>

                    <div class="details">
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
                        <p><strong>Duration:</strong> <?php echo $duration; ?></p>
                        <p><strong>Date:</strong> <?php echo $formattedDate; ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['VENUE']); ?></p>
                        <p><strong>Status:</strong> <?php echo $statusLabel; ?></p>
                        <p><strong>Registration:</strong> <?php echo $registrationLabel; ?></p>
                        <p><strong>Organizer:</strong> <?php echo htmlspecialchars($event['ORG_NAME'] ?? 'N/A'); ?></p>
                    </div>

                    <div class="description">
                        <h3>Description:</h3>
                        <p> <?php echo nl2br(htmlspecialchars($event['DESCRIPTION'])); ?> </p>
                    </div>
                </div>

                <div class="image-column">
                    <div class="carousel image-carousel">
                        <div class="carousel-track" id="imageTrack"></div>
                        <button class="carousel-btn prev" id="imgPrev">&#10094;</button>
                        <button class="carousel-btn next" id="imgNext">&#10095;</button>
                        <div class="carousel-dots" id="imageDots"></div>
                    </div>
                </div>
            </section>
        </main>

        <script>
            const eventImages = <?php echo $bannerImage !== '' ? json_encode([$bannerImage]) : '[]'; ?>;
        </script>
        <script src="js/admin-event-review.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
