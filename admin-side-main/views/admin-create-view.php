<?php
$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Edit Organization' : 'Create Organization';
$submitLabel = $isEdit ? 'Save Changes' : 'Create';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $pageTitle; ?></title>
        <meta charset="UTF-8">
<link rel="stylesheet" href="../assets/styles/admin/darkmode.css">
<link rel="stylesheet" href="../assets/styles/admin/admin-create.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body class="s-admin-admin-create s-admin-darkmode">
        <nav class="navbar">
            <div class="nav-left">
                <a href="admin-dashboard.php" class="nav-left">
                <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
                <span class="logo-text">
                    WhatsUpDLSU
                </span>
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
                        <button>EDIT USER DETAILS</button>
                        <button onclick="window.location.href='../login-side-main/logout.php'"> LOG OUT </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="create-page">

            <div class="header-row">
                <button class="back-btn" onclick="location.href='account-management.php'"> ◀ Account Management </button>
                <h1 class="page-title"><?php echo $pageTitle; ?></h1>
                <div class="header-spacer"></div>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="form-card" style="border: 1px solid #e33; margin-bottom: 12px;">
                    <p style="color:#e33; margin:0;"><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="save-organization.php" id="orgForm">
                <input type="hidden" name="user_id" value="<?php echo $isEdit ? (int) $orgId : ''; ?>">

                <div class="form-card">
                    <h3 class="section-heading">Account Information</h3>

                    <div class="form-group">
                        <label>Organization Name <span class="required-badge">required</span></label>
                        <input type="text" name="org_name" placeholder="Enter organization name"
                               value="<?php echo htmlspecialchars($orgName); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Password <span class="required-badge">required</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="passwordInput" name="password" placeholder="Enter password"
                                   value="<?php echo htmlspecialchars($password); ?>" required>
                            <button type="button" id="togglePassword" class="show-password-btn">Show</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Retype Password <span class="required-badge">required</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="retypePasswordInput" name="retype_password" placeholder="Re-enter password"
                                   value="<?php echo htmlspecialchars($password); ?>" required>
                            <button type="button" id="toggleRetypePassword" class="show-password-btn">Show</button>
                        </div>
                        <span id="passwordMatchMessage" class="field-hint"></span>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="create-btn" id="createBtn"> <?php echo $submitLabel; ?> </button>
                    </div>
                </div>
            </form>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div id="php-success-msg" data-message="<?php echo htmlspecialchars($_SESSION['success_message']); ?>"></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
        </main>

<script src="../assets/scripts/admin/darkmode.js"></script>
<script src="../assets/scripts/admin/admin-create.js"></script>

    </body>
</html>
