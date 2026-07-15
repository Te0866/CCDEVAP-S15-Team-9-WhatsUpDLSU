<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/edit-event.css">
    <link rel="stylesheet" href="css/darkmode.css">
    <link rel="stylesheet" href="css/modal.css">
</head>

<body>

    <?php $activeNav = 'manage'; include __DIR__ . "/partials/navbar.view.php"; ?>

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
            <input type="hidden" name="remove_image" id="removeImageFlag" value="0">

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
                                <div class="file-chip" id="existingImageChip">
                                    <span><?php echo htmlspecialchars($event['BANNER_IMAGE']); ?></span>
                                    <button type="button" id="removeFile">&times;</button>
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

    <script src="js/modal.js"></script>
    <script src="js/edit-event.js"></script>
    <script src="js/darkmode.js"></script>

</body>

</html>
