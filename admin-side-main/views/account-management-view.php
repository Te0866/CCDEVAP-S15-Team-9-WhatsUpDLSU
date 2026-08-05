<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Account Management</title>

<link rel="stylesheet" href="../assets/styles/admin/darkmode.css">
<link rel="stylesheet" href="../assets/styles/admin/account-management.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body class="s-admin-account-management s-admin-darkmode">
        <nav class="navbar">
            <div class="nav-left">
                <a href="admin-dashboard.php" class="nav-left">
                <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
                <span class="logo-text"> WhatsUpDLSU </span>
                </a>
            </div>

            <div class="nav-right">
                <div class="nav-links">
                    <a href="admin-dashboard.php" class="nav-tab"> Manage Events </a>
                    <a href="account-management.php" class="nav-tab active"> Account Management </a>
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
            <h1>Account Management</h1>

            <div class="header-actions">
                <button class="add-user-btn" onclick="location.href='add-student.php'"> + Add Student </button>
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
                                        : 'add-student.php?id=' . $account['user_id'];
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
        </main>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Delete Account</h2>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this account?
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelDeleteBtn" class="modal-btn modal-btn-secondary">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="modal-btn modal-btn-danger">Delete</button>
                </div>
            </div>
        </div>
<script src="../assets/scripts/admin/darkmode.js"></script>
<script src="../assets/scripts/admin/account-management.js"></script>

    </body>
</html>
