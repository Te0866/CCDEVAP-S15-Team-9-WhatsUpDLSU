<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Moderator Dashboard</title>

        <link rel="stylesheet" href="css/admin-dashboard.css">
        <link rel="stylesheet" href="css/darkmode.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <section class="stats-section">
            <div class="stat-card">
                <span class="stat-label">Pending</span>
                <span class="stat-count" id="pendingCount"><?php echo $counts['PENDING']; ?></span>
            </div>

            <div class="stat-card">
                <span class="stat-label">Approved</span>
                <span class="stat-count" id="approvedCount"><?php echo $counts['APPROVED']; ?></span>
            </div>

            <div class="stat-card">
                <span class="stat-label">Rejected</span>
                <span class="stat-count" id="rejectedCount"><?php echo $counts['REJECTED']; ?></span>
            </div>

            <div class="stat-card">
                <span class="stat-label">Registered Orgs</span>
                <span class="stat-count" id="orgsCount"><?php echo $orgsCount; ?></span>
            </div>
        </section>

        <div class="search-section">
            <input type="text" id="searchInput" placeholder="Search Events" class="search-input"
                   value="<?php echo htmlspecialchars($searchValue ?? ''); ?>">

            <input type="date" id="filterDate" class="filter-box" value="<?php echo htmlspecialchars($dateValue ?? ''); ?>">

            <select id="filterCategory" class="filter-box">
                <option value="" <?php echo ($categoryValue ?? '') === '' ? 'selected' : ''; ?>>Category</option>
                <option value="ACADEMIC" <?php echo ($categoryValue ?? '') === 'ACADEMIC' ? 'selected' : ''; ?>>Academic</option>
                <option value="CAREER" <?php echo ($categoryValue ?? '') === 'CAREER' ? 'selected' : ''; ?>>Career</option>
                <option value="NON-ACADEMIC" <?php echo ($categoryValue ?? '') === 'NON-ACADEMIC' ? 'selected' : ''; ?>>Non-Academic</option>
            </select>

            <select id="filterStatus" class="filter-box">
                <option value="" <?php echo ($statusValue ?? '') === '' ? 'selected' : ''; ?>>Status</option>
                <option value="PENDING" <?php echo ($statusValue ?? '') === 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                <option value="APPROVED" <?php echo ($statusValue ?? '') === 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
                <option value="REJECTED" <?php echo ($statusValue ?? '') === 'REJECTED' ? 'selected' : ''; ?>>Rejected</option>
            </select>

            <button type="button" class="view-btn" onclick="location.href='admin-dashboard.php'">Clear Filters</button>
        </div>

        <main class="manage-container">
            <div class="table-wrapper">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Category</th>
                            <th>Duration</th>
                            <th>Venue</th>
                            <th>Organizer</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="eventsTableBody">
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">No events found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <?php
                                    $statusClass = strtolower($event['APPROVAL_STATUS']);
                                    $statusLabel = ucfirst($statusClass);
                                    $duration = date("g:i A", strtotime($event['START_TIME'])) . " - " . date("g:i A", strtotime($event['END_TIME']));
                                ?>
                                <tr class="event-row" data-status="<?php echo $statusClass; ?>" data-date="<?php echo $event['DATE']; ?>">
                                    <td data-label="Event Name"><?php echo htmlspecialchars($event['TITLE']); ?></td>
                                    <td data-label="Category"><?php echo htmlspecialchars(ucwords(strtolower(str_replace('-', ' ', $event['CATEGORY'])))); ?></td>
                                    <td data-label="Duration"><?php echo $duration; ?></td>
                                    <td data-label="Venue"><?php echo htmlspecialchars($event['VENUE']); ?></td>
                                    <td data-label="Organizer"><?php echo htmlspecialchars($event['ORG_NAME'] ?? 'N/A'); ?></td>
                                    <td data-label="Status"><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                    <td data-label="Actions" class="actions-cell">
                                        <button class="view-btn" onclick="location.href='admin-event-review.php?event_id=<?php echo $event['EVENT_ID']; ?>'"> View </button>

                                        <form method="POST" action="update-event-status.php" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo $event['EVENT_ID']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="redirect" value="admin-dashboard.php">
                                            <button type="submit" class="approve-btn"> Approve </button>
                                        </form>

                                        <form method="POST" action="update-event-status.php" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo $event['EVENT_ID']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="redirect" value="admin-dashboard.php">
                                            <button type="submit" class="reject-btn"> Reject </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <script src="js/admin-dashboard.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
