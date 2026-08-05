<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Moderator Dashboard</title>

<link rel="stylesheet" href="../assets/styles/admin/darkmode.css">
<link rel="stylesheet" href="../assets/styles/admin/admin-dashboard.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body class="s-admin-admin-dashboard s-admin-darkmode">
        <nav class="navbar">
            <div class="nav-left">
                <a href="admin-dashboard.php" class="nav-left">
                <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
                <span class="logo-text"> WhatsUpDLSU </span>
                </a>
            </div>

            <div class="nav-right">
                <div class="nav-links">
                    <a href="admin-dashboard.php" class="nav-tab active"> Manage Events </a>
                    <a href="account-management.php" class="nav-tab"> Account Management </a>
                    <a href="comments-management.php" class="nav-tab"> Manage Comments </a>
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

        <section class="page-header">
            <h1>Manage Events</h1>

            <div class="header-actions">
                <button class="add-user-btn" onclick="location.href='add-event.php'"> + Add Event </button>
            </div>
        </section>

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
                <div class="category-legend">
                    <span class="category-legend-label">Category:</span>
                    <span class="category-legend-item"><span class="category-legend-dot academic"></span>Academic</span>
                    <span class="category-legend-item"><span class="category-legend-dot nonacademic"></span>Non-Academic</span>
                    <span class="category-legend-item"><span class="category-legend-dot career"></span>Career</span>
                </div>
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Time</th>
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
                                    $categoryClass = strtolower(str_replace('-', '', $event['CATEGORY']));
                                    $formattedDate = date("M j, Y", strtotime($event['DATE']));
                                    $duration = date("g:i A", strtotime($event['START_TIME'])) . " - " . date("g:i A", strtotime($event['END_TIME']));
                                ?>
                                <tr class="event-row" data-status="<?php echo $statusClass; ?>" data-category="<?php echo $categoryClass; ?>" data-date="<?php echo $event['DATE']; ?>">
                                    <td data-label="Event Name"><span class="event-name-badge <?php echo $categoryClass; ?>"><?php echo htmlspecialchars($event['TITLE']); ?></span></td>
                                    <td data-label="Date"><?php echo $formattedDate; ?></td>
                                    <td data-label="Time"><?php echo $duration; ?></td>
                                    <td data-label="Venue"><?php echo htmlspecialchars($event['VENUE']); ?></td>
                                    <td data-label="Organizer"><?php echo htmlspecialchars($event['ORG_NAME'] ?? 'N/A'); ?></td>
                                    <td data-label="Status"><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                    <td data-label="Actions" class="actions-cell">
                                        <button class="view-btn" onclick="location.href='admin-event-review.php?event_id=<?php echo $event['EVENT_ID']; ?>'"> View </button>

                                        <form method="POST" action="update-event-status.php" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo $event['EVENT_ID']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="redirect" value="admin-dashboard.php">
                                            <button type="submit" class="approve-btn" <?php echo $statusClass === 'approved' ? 'disabled' : ''; ?>> Approve </button>
                                        </form>

                                        <form method="POST" action="update-event-status.php" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo $event['EVENT_ID']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="redirect" value="admin-dashboard.php">
                                            <button type="submit" class="reject-btn" <?php echo $statusClass === 'rejected' ? 'disabled' : ''; ?>> Reject </button>
                                        </form>

                                        <button class="edit-btn" onclick="location.href='add-event.php?id=<?php echo $event['EVENT_ID']; ?>'"> Edit </button>

                                        <form method="POST" action="delete-event.php" class="delete-form" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo $event['EVENT_ID']; ?>">
                                            <button type="button" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Delete Event</h2>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this event? This cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelDeleteBtn" class="modal-btn modal-btn-secondary">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="modal-btn modal-btn-danger">Delete</button>
                </div>
            </div>
        </div>

<script src="../assets/scripts/admin/darkmode.js"></script>
<script src="../assets/scripts/admin/admin-dashboard.js"></script>

    </body>
</html>
