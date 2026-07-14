<?php
/**
 * Expects (set by controllers):
 * @var array  $accounts      rows from AccountController::listAccounts()
 * @var string $searchValue   current search query
 * @var string $typeValue     current type filter ('all'|'student'|'organization')
 * @var string $adminName     display name for the profile button
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Account Management</title>

        <link rel="stylesheet" href="css/account-management.css">
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
                    <a href="admin-dashboard.php" class="nav-tab"> Manage Events </a>
                    <a href="account-management.php" class="nav-tab active"> Account Management </a>
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
            <h1>Account Management</h1>

            <div class="header-actions">
                <button class="add-user-btn" onclick="location.href='add-user.php'"> + Add User </button>
                <button class="create-org-btn" onclick="location.href='admin-create.php'"> + Create Organization </button>
            </div>
        </section>

        <div class="search-section">
            <input type="text" id="searchInput" placeholder="Search Accounts" class="search-input"
                value="<?php echo htmlspecialchars($searchValue ?? ''); ?>">

            <select id="typeFilter" class="filter-box">
                <option value="all" <?php echo ($typeValue ?? '') === 'all' ? 'selected' : ''; ?>>All Accounts</option>
                <option value="student" <?php echo ($typeValue ?? '') === 'student' ? 'selected' : ''; ?>>Students</option>
                <option value="organization" <?php echo ($typeValue ?? '') === 'organization' ? 'selected' : ''; ?>>Officers/Organizations</option>
            </select>

            <button type="button" class="add-user-btn" onclick="location.href='account-management.php'">Clear Filters</button>
        </div>

        <main class="users-container">
            <div class="table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Account Type</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="usersTableBody">
                        <?php if (empty($accounts)): ?>
                            <tr>
                                <td colspan="4" style="text-align:center;">No accounts found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($accounts as $account): ?>
                                <?php
                                    $isOrg = $account['type'] === 'organization';
                                    $editHref = $isOrg
                                        ? 'admin-create.php?id=' . $account['user_id']
                                        : 'add-user.php?id=' . $account['user_id'];
                                    $formattedDate = date("F j, Y", strtotime($account['created_at']));
                                ?>
                                <tr class="user-row" data-type="<?php echo $account['type']; ?>">
                                    <td data-label="Name"><?php echo htmlspecialchars($account['name']); ?></td>
                                    <td data-label="Account Type">
                                        <span class="type-badge <?php echo $isOrg ? 'organization' : 'student'; ?>">
                                            <?php echo $isOrg ? 'Officer/Organization' : 'Student'; ?>
                                        </span>
                                    </td>
                                    <td data-label="Date Added"><?php echo $formattedDate; ?></td>
                                    <td data-label="Actions" class="actions-cell">
                                        <button class="edit-btn" onclick="location.href='<?php echo $editHref; ?>'"> Edit </button>
                                        <form method="POST" action="delete-account.php" class="delete-form" style="display:inline;">
                                            <input type="hidden" name="type" value="<?php echo $account['type']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $account['user_id']; ?>">
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
                <h2>Delete Account</h2>
                <p>Are you sure you want to delete this account?</p>

                <div class="modal-buttons">
                    <button id="cancelDeleteBtn"  class="cancel-btn">Cancel</button>
                    <button id="confirmDeleteBtn" class="delete-btn">Delete</button>
                </div>
            </div>
        </div>
        <script src="js/account-management.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>