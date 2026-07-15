<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage Events</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/manage.css">
        <link rel="stylesheet" href="css/darkmode.css">
    </head>

    <body>
        <?php $activeNav = 'manage'; include __DIR__ . "/partials/navbar.view.php"; ?>

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

        <div class="search-section" id="filterSection">
            <input type="text" id="searchInput" placeholder="Search Events" class="search-input" autocomplete="off">

            <input type="date" id="filterDate" class="filter-box">

            <select id="filterCategory" class="filter-box">
                <option value="">Category</option>
                <option value="ACADEMIC">Academic</option>
                <option value="CAREER">Career</option>
                <option value="NON-ACADEMIC">Non-Academic</option>
            </select>

            <select id="filterStatus" class="filter-box">
                <option value="">Status</option>
                <option value="PENDING">Pending</option>
                <option value="APPROVED">Approved</option>
                <option value="REJECTED">Rejected</option>
            </select>

            <button type="button" id="clearFiltersBtn" class="view-btn">Clear</button>
        </div>

        <main class="manage-container">

            <p id="noResultsMsg" style="display:none;">No events match your search/filters.</p>

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

                        <div class="event-card <?php echo $cardClass; ?>"
                             data-title="<?php echo htmlspecialchars(strtolower($event['TITLE'])); ?>"
                             data-date="<?php echo htmlspecialchars($event['DATE']); ?>"
                             data-category="<?php echo htmlspecialchars($event['CATEGORY']); ?>"
                             data-status="<?php echo htmlspecialchars($event['APPROVAL_STATUS']); ?>">
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
