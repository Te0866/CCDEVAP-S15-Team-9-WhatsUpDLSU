<?php
$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Edit Event' : 'Add Event';
$submitLabel = $isEdit ? 'Save Changes' : 'Create Event';

$categories = ['ACADEMIC' => 'Academic', 'NON-ACADEMIC' => 'Non-Academic', 'CAREER' => 'Career'];
$statuses = ['PENDING' => 'Pending', 'APPROVED' => 'Approved', 'REJECTED' => 'Rejected'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pageTitle; ?></title>

        <link rel="stylesheet" href="css/add-event.css">
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

        <main class="create-page">

            <div class="header-row">
                <button class="back-btn" onclick="location.href='admin-dashboard.php'"> ◀ Manage Events </button>
                <h1 class="page-title" id="formTitle"><?php echo $pageTitle; ?></h1>
                <div class="header-spacer"></div>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="form-card" style="border: 1px solid #e33; margin-bottom: 12px;">
                    <p style="color:#e33; margin:0;"><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="save-event.php">
                <input type="hidden" name="event_id" value="<?php echo $isEdit ? (int) $eventId : ''; ?>">

                <div class="form-card">
                    <h3 class="section-heading">Event Information</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Event Title <span class="required-badge">required</span></label>
                            <input type="text" name="title" placeholder="Enter event title"
                                   value="<?php echo htmlspecialchars($title); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Category <span class="required-badge">required</span></label>
                            <select name="category" required>
                                <?php foreach ($categories as $value => $label): ?>
                                    <option value="<?php echo $value; ?>" <?php echo $category === $value ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label>Description <span class="required-badge">required</span></label>
                            <textarea name="description" placeholder="Enter event description" required><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Location <span class="required-badge">required</span></label>
                            <input type="text" name="location" placeholder="e.g. Gokongwei Hall (GOKONGWEI)"
                                   value="<?php echo htmlspecialchars($location); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Venue / Room <span class="required-badge">required</span></label>
                            <input type="text" name="venue" placeholder="e.g. 304B"
                                   value="<?php echo htmlspecialchars($venue); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date <span class="required-badge">required</span></label>
                            <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Organizer <span class="required-badge">required</span></label>
                            <select name="user_id" required>
                                <option value="">Select organizer</option>
                                <?php foreach ($organizers as $org): ?>
                                    <option value="<?php echo (int) $org['USER_ID']; ?>" <?php echo (int) $userId === (int) $org['USER_ID'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($org['USER_NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Time <span class="required-badge">required</span></label>
                            <input type="time" name="start_time" value="<?php echo htmlspecialchars($startTime); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>End Time <span class="required-badge">required</span></label>
                            <input type="time" name="end_time" value="<?php echo htmlspecialchars($endTime); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Approval Status <span class="required-badge">required</span></label>
                            <select name="approval_status" required>
                                <?php foreach ($statuses as $value => $label): ?>
                                    <option value="<?php echo $value; ?>" <?php echo $approvalStatus === $value ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Registration</label>
                            <div class="checkbox-group">
                                <input type="checkbox" id="registrationStatus" name="registration_status" value="1"
                                       <?php echo $registrationStatus ? 'checked' : ''; ?>>
                                <label for="registrationStatus">Registration Open</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" placeholder="Optional remarks for the organizer"><?php echo htmlspecialchars($remarks); ?></textarea>
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

        <script src="js/add-event.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
