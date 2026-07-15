<!DOCTYPE html>
<html lang="en">
<head>
    <title>WhatsUpDLSU - Edit Organization</title>

    <link rel="stylesheet" href="css/edit-organization.css">
    <link rel="stylesheet" href="css/darkmode.css">
</head>

<body>
    <?php $activeNav = 'organization'; include __DIR__ . "/partials/navbar.view.php"; ?>

    <main class="edit-page">

        <div class="header-row">

            <button class="back-btn" onclick="location.href='officer-dashboard.php'">
                ◀ Dashboard
            </button>

            <h1 class="page-title">Edit Organization Details</h1>

            <div class="header-spacer"></div>

        </div>

        <div class="form-card">

            <h3 class="section-heading">Account Information</h3>

            <div class="form-group profile-upload">
                <label>Profile Picture <span class="optional-badge">optional</span></label>
                <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile Picture" id="profilePreview" class="profile-preview">
                <input type="file" id="profileImage" name="profileImage" accept="image/*" hidden>
                <button type="button" class="upload-btn" id="uploadPicBtn">Upload Image</button>
            </div>

            <div class="form-group">
                <label>Organization Name <span class="required-badge">required</span></label>
                <input type="text" id="orgName" name="orgName" value="<?php echo htmlspecialchars($user['USER_NAME']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password <span class="required-badge">required</span></label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['PASSWORD']); ?>" required>
                    <button type="button" id="togglePassword" class="show-password-btn">Show</button>
                </div>
            </div>

            <p id="formError" class="form-error" style="color:#c0392b; font-weight:bold; display:none; margin-top:-10px; margin-bottom:15px;"></p>

            <div class="button-group">
                <button class="update-btn" id="updateBtn"> Update Details </button>
            </div>

        </div>

    </main>

    <script src="js/edit-organization.js"></script>
    <script src="js/darkmode.js"></script>
</body>
</html>
