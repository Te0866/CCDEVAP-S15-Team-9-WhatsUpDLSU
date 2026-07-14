<?php
/**
 * Expects:
 * @var string  $mode      'create' | 'edit'
 * @var ?int    $userId
 * @var string  $username
 * @var string  $password
 * @var string  $adminName
 * @var ?string $errorMessage
 */
$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Edit User' : 'Add User';
$submitLabel = $isEdit ? 'Save Changes' : 'Create User';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pageTitle; ?></title>

        <link rel="stylesheet" href="css/add-user.css">
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

        <main class="create-page">

            <div class="header-row">
                <button class="back-btn" onclick="location.href='account-management.php'"> ◀ Account Management </button>
                <h1 class="page-title" id="formTitle"><?php echo $pageTitle; ?></h1>
                <div class="header-spacer"></div>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="form-card" style="border: 1px solid #e33; margin-bottom: 12px;">
                    <p style="color:#e33; margin:0;"><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="save-user.php">
                <input type="hidden" name="user_id" value="<?php echo $isEdit ? (int) $userId : ''; ?>">

                <div class="form-card">
                    <h3 class="section-heading">Account Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Username <span class="required-badge">required</span></label>
                            <input type="text" id="usernameInput" name="username" placeholder="Enter username"
                                   value="<?php echo htmlspecialchars($username); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Password <span class="required-badge">required</span></label>
                            <input type="password" id="passwordInput" name="password" placeholder="Enter password"
                                   value="<?php echo htmlspecialchars($password); ?>" required>
                        </div>
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

        <script src="js/add-user.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
