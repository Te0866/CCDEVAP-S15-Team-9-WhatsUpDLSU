<?php
$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Edit Comment' : 'Add Comment';
$submitLabel = $isEdit ? 'Save Changes' : 'Create Comment';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pageTitle; ?></title>

        <link rel="stylesheet" href="css/add-comment.css">
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

        <main class="create-page">

            <div class="header-row">
                <button class="back-btn" onclick="location.href='comments-management.php'"> ◀ Comments Management </button>
                <h1 class="page-title" id="formTitle"><?php echo $pageTitle; ?></h1>
                <div class="header-spacer"></div>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="form-card" style="border: 1px solid #e33; margin-bottom: 12px;">
                    <p style="color:#e33; margin:0;"><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="save-comment.php">
                <input type="hidden" name="comment_id" value="<?php echo $isEdit ? (int) $commentId : ''; ?>">

                <div class="form-card">
                    <h3 class="section-heading">Comment Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Event <span class="required-badge">required</span></label>
                            <select name="event_id" required>
                                <option value="">Select event</option>
                                <?php foreach ($events as $ev): ?>
                                    <option value="<?php echo (int) $ev['EVENT_ID']; ?>" <?php echo (int) $eventId === (int) $ev['EVENT_ID'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($ev['TITLE']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Username <span class="required-badge">required</span></label>
                            <input type="text" name="username" placeholder="Enter commenter's username"
                                   value="<?php echo htmlspecialchars($username); ?>" required>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label>Comment Text <span class="required-badge">required</span></label>
                            <textarea name="text" placeholder="Enter comment text" maxlength="200" required><?php echo htmlspecialchars($text); ?></textarea>
                            <div class="char-counter">Max 200 characters</div>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="isAnonymous" name="is_anonymous" value="1"
                                       <?php echo $isAnonymous ? 'checked' : ''; ?>>
                                <label for="isAnonymous">Post as Anonymous</label>
                            </div>
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

        <script src="js/add-comment.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
