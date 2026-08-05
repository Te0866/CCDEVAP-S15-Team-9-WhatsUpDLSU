<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/styles/org/darkmode.css">
<link rel="stylesheet" href="../assets/styles/org/modal.css">
<link rel="stylesheet" href="../assets/styles/org/edit-event.css">
</head>

<body class="s-org-edit-event s-org-darkmode s-org-modal">

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

            <?php if ($event['APPROVAL_STATUS'] === 'REJECTED' && trim($event['REMARKS'] ?? '') !== '') { ?>
                <div class="remarks-banner">
                    <strong>This event was rejected.</strong>
                    <p><?php echo htmlspecialchars($event['REMARKS']); ?></p>
                    <span class="remarks-banner-note">Saving changes will resubmit this event for approval.</span>
                </div>
            <?php } else if ($event['APPROVAL_STATUS'] === 'APPROVED') { ?>
                <div class="remarks-banner remarks-banner-info">
                    <span class="remarks-banner-note">This event is approved. Saving changes will send it back to Pending for admin re-review.</span>
                </div>
            <?php } ?>

            <input type="hidden" name="event_id" value="<?php
                                                            echo $event['EVENT_ID'];
                                                        ?>">
            <?php
                $bannerImages = [];
                if (!empty($event['BANNER_IMAGE'])) {
                    foreach (explode(',', $event['BANNER_IMAGE']) as $img) {
                        $img = trim($img);
                        if ($img !== '') {
                            $bannerImages[] = $img;
                        }
                    }
                }
            ?>
            <input type="hidden" name="existing_images" id="existingImagesInput" value="<?php echo htmlspecialchars(implode(',', $bannerImages)); ?>">
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
                        <label>Event Images <span class="optional-badge">optional</span></label>

                        <div class="upload-box">
                            <div class="upload-icon">📁</div>
                            <p>Click to upload or drag and drop (up to 4 images)</p>
                            <input type="file" id="eventImages" name="event_images[]" accept="image/*" multiple>
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

        <section class="comments-card">
            <h3 class="section-heading">Student Comments</h3>
            <p class="comments-subtext">Remove comments that are spam, inappropriate, or off-topic.</p>

            <ul class="comments-list" id="commentsList">
                <li class="comments-loading">Loading comments...</li>
            </ul>
        </section>

    </main>

    <script>
        const currentEventId = <?php echo (int) $event['EVENT_ID']; ?>;
    </script>

<script src="../assets/scripts/org/darkmode.js"></script>
<script src="../assets/scripts/org/modal.js"></script>
<script src="../assets/scripts/org/edit-event.js"></script>

</body>

</html>
