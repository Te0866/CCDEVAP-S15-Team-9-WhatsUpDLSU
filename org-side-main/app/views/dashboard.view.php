<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsUpDLSU - Officer Dashboard</title>
<link rel="stylesheet" href="../assets/styles/org/darkmode.css">
<link rel="stylesheet" href="../assets/styles/org/modal.css">
<link rel="stylesheet" href="../assets/styles/org/officer-dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="s-org-officer-dashboard s-org-darkmode s-org-modal">

    <?php $activeNav = 'home'; include __DIR__ . "/partials/navbar.view.php"; ?>

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
                <span class="stat-label"> Total events </span>
                <span class="stat-value" id="statTotal"> <?php echo $totalCount; ?> </span>
            </div>

            <div class="stat-card">
                <span class="stat-label"> Active events </span>
                <span class="stat-value" id="statActive"> <?php echo $activeCount; ?> </span>
            </div>

            <div class="stat-card">
                <span class="stat-label"> Pending approval </span>
                <span class="stat-value" id="statPending"> <?php echo $pendingCount; ?> </span>
            </div>

            <div class="stat-card">
                <span class="stat-label"> Rejected </span>
                <span class="stat-value" id="statRejected"> <?php echo $rejectedCount; ?> </span>
            </div>

            <div class="stat-card">
                <span class="stat-label"> Students interested </span>
                <span class="stat-value" id="statInterest"> <?php echo $totalInterestCount; ?> </span>
            </div>
        </section>

        <?php if (empty($attentionItems)) { ?>
        <section class="attention-bar">
            <span class="attention-ok">Nothing needs your attention (no rejected events or approvals running out of time).</span>
        </section>
        <?php } else { ?>
        <section class="attention-bar has-items">
            <div class="attention-heading">Needs your attention</div>
            <ul class="attention-list">
                <?php foreach ($attentionItems as $item) { ?>
                    <li class="attention-item">
                        <span class="attention-item-text">
                            <span class="attention-tag <?php echo strtolower($item['type']); ?>"><?php echo $item['type']; ?></span>
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong> <?php echo htmlspecialchars($item['message']); ?>
                        </span>
                        <a class="attention-link" href="edit-event.php?event_id=<?php echo (int) $item['event_id']; ?>">Review &rarr;</a>
                    </li>
                <?php } ?>
            </ul>
        </section>
        <?php } ?>

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
                                echo "<li class=\"activity-empty\">No recent activity yet.</li>";
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
                    <h2> Category vs. approval outcome </h2>
                    <p class="chart-subtext">Approval breakdown by event category.</p>
                    <div class="chart-wrap chart-wrap-tall">
                        <canvas id="categoryApprovalChart"></canvas>
                        <p class="chart-empty" id="categoryApprovalChartEmpty">No events yet — this fills in once you create one.</p>
                    </div>

                    <div class="legend">
                        <span class="legend-item"><span class="color-box status-approved-box"></span> Approved</span>
                        <span class="legend-item"><span class="color-box status-pending-box"></span> Pending</span>
                        <span class="legend-item"><span class="color-box status-rejected-box"></span> Rejected</span>
                    </div>
                </section>
            </div>

        </div>

        <section class="charts-grid">
            <div class="chart-box">
                <h2> Events per location </h2>
                <div class="chart-wrap">
                    <canvas id="locationChart"></canvas>
                    <p class="chart-empty" id="locationChartEmpty">No location data yet.</p>
                </div>
            </div>

            <div class="chart-box">
                <h2> Most interested events </h2>
                <div class="chart-wrap">
                    <canvas id="interestChart"></canvas>
                    <p class="chart-empty" id="interestChartEmpty">No interest data yet.</p>
                </div>
            </div>

            <div class="chart-box">
                <h2> Interest by event date </h2>
                <p class="chart-subtext">Student interest plotted by event date.</p>
                <div class="chart-wrap">
                    <canvas id="scatterChart"></canvas>
                    <p class="chart-empty" id="scatterChartEmpty">No interest data yet.</p>
                </div>

                <div class="legend">
                    <span class="legend-item"><span class="color-box category-academic-box"></span> Academic</span>
                    <span class="legend-item"><span class="color-box category-nonacademic-box"></span> Non-academic</span>
                    <span class="legend-item"><span class="color-box category-career-box"></span> Career</span>
                </div>
            </div>
        </section>
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
        const categoryLabels = ["Academic", "Non-academic", "Career"];
        const categoryApprovedData = [
            <?php echo $categoryApprovalBreakdown['ACADEMIC']['APPROVED']; ?>,
            <?php echo $categoryApprovalBreakdown['NON-ACADEMIC']['APPROVED']; ?>,
            <?php echo $categoryApprovalBreakdown['CAREER']['APPROVED']; ?>
        ];
        const categoryPendingData = [
            <?php echo $categoryApprovalBreakdown['ACADEMIC']['PENDING']; ?>,
            <?php echo $categoryApprovalBreakdown['NON-ACADEMIC']['PENDING']; ?>,
            <?php echo $categoryApprovalBreakdown['CAREER']['PENDING']; ?>
        ];
        const categoryRejectedData = [
            <?php echo $categoryApprovalBreakdown['ACADEMIC']['REJECTED']; ?>,
            <?php echo $categoryApprovalBreakdown['NON-ACADEMIC']['REJECTED']; ?>,
            <?php echo $categoryApprovalBreakdown['CAREER']['REJECTED']; ?>
        ];

        const locationLabels = <?php echo json_encode(array_keys($locationCounts)); ?>;
        const locationData = <?php echo json_encode(array_values($locationCounts)); ?>;

        const interestLabels = <?php
            $topInterestedResult->data_seek(0);
            $labels = [];
            $data = [];
            while ($row = mysqli_fetch_assoc($topInterestedResult)) {
                $labels[] = $row['TITLE'];
                $data[] = (int) $row['interest_total'];
            }
            echo json_encode($labels);
        ?>;
        const interestData = <?php echo json_encode($data); ?>;

        const scatterDateLabels = <?php echo json_encode(array_values(array_unique(array_column($scatterData, 'date')))); ?>;
        const scatterPoints = <?php echo json_encode(array_map(function ($p) {
            return ['x' => $p['date'], 'y' => $p['interest'], 'category' => $p['category'], 'title' => $p['title']];
        }, $scatterData)); ?>;
    </script>

<script src="../assets/scripts/org/darkmode.js"></script>
<script src="../assets/scripts/org/colors.js"></script>
<script src="../assets/scripts/org/modal.js"></script>
<script src="../assets/scripts/org/officer-dashboard.js"></script>

</body>
</html>
