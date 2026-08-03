<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Comments Management</title>

<link rel="stylesheet" href="../assets/css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body class="s-admin-comments-management s-admin-darkmode">
        <nav class="navbar">
            <div class="nav-left">
                <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
                <span class="logo-text"> WhatsUpDLSU </span>
            </div>

            <div class="nav-right">
                <div class="nav-links">
                    <a href="admin-dashboard.php" class="nav-tab"> Manage Events </a>
                    <a href="account-management.php" class="nav-tab"> Account Management </a>
                    <a href="comments-management.php" class="nav-tab active"> Manage Comments </a>
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
            <h1>Comments Management</h1>

            <div class="header-actions">
                <button class="add-user-btn" onclick="location.href='add-comment.php'"> + Add Comment </button>
            </div>
        </section>

        <div class="search-section">
            <input type="text" id="searchInput" placeholder="Search by username or comment text" class="search-input"
                value="<?php echo htmlspecialchars($searchValue ?? ''); ?>">

            <select id="eventFilter" class="filter-box">
                <option value="all" <?php echo ($eventValue ?? '') === '' ? 'selected' : ''; ?>>All Events</option>
                <?php foreach ($events as $ev): ?>
                    <option value="<?php echo (int) $ev['EVENT_ID']; ?>" <?php echo (string) ($eventValue ?? '') === (string) $ev['EVENT_ID'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($ev['TITLE']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="button" class="add-user-btn" onclick="location.href='comments-management.php'">Clear Filters</button>
        </div>

        <main class="users-container">
            <div class="table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Comment</th>
                            <th>Event</th>
                            <th>Anonymous</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="commentsTableBody">
                        <?php if (empty($comments)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No comments found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <tr class="user-row" data-event-id="<?php echo $comment['event_id']; ?>">
                                    <td data-label="Username"><?php echo htmlspecialchars($comment['username']); ?></td>
                                    <td data-label="Comment"><?php echo htmlspecialchars($comment['text']); ?></td>
                                    <td data-label="Event"><?php echo htmlspecialchars($comment['event_title']); ?></td>
                                    <td data-label="Anonymous">
                                        <span class="type-badge <?php echo $comment['is_anonymous'] ? 'organization' : 'student'; ?>">
                                            <?php echo $comment['is_anonymous'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions" class="actions-cell">
                                        <button class="edit-btn" onclick="location.href='add-comment.php?id=<?php echo $comment['comment_id']; ?>'"> Edit </button>
                                        <form method="POST" action="delete-comment.php" class="delete-form" style="display:inline;">
                                            <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                            <button type="button" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div id="php-success-msg" data-message="<?php echo htmlspecialchars($_SESSION['success_message']); ?>"></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
        </main>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h2>Delete Comment</h2>
                <p>Are you sure you want to delete this comment?</p>

                <div class="modal-buttons">
                    <button id="cancelDeleteBtn" class="cancel-btn">Cancel</button>
                    <button id="confirmDeleteBtn" class="delete-btn">Delete</button>
                </div>
            </div>
        </div>
<script src="../assets/js/script.js"></script>

    </body>
</html>
