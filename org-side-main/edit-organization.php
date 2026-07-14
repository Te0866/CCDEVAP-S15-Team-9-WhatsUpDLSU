<?php
    session_start();
    require_once __DIR__ . "/../dbconnection.php";

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
        header("Location: ../login-side-main/officer-login.html");
        exit;
    }

    $userId = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE USER_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        die("User not found.");
    }

    require_once __DIR__ . "/../profile-picture.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>WhatsUpDLSU - Edit Organization</title>

    <link rel="stylesheet" href="css/edit-organization.css">
    <link rel="stylesheet" href="css/darkmode.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-left">
            <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
            <span class="logo-text"> WhatsUpDLSU </span>
        </div>

        <div class="nav-right">
            <div class="nav-links">
                <a href="officer-dashboard.php" class="nav-tab"> Home </a>
                <a href="create.php" class="nav-tab"> Create </a>
                <a href="manage.php" class="nav-tab"> Manage </a>
            </div>

            <div class="profile-section">
                <button class="profile-btn" id="profileBtn">
                    <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile" class="profile-pic">
                </button>
                <div class="dropdown-menu" id="dropdownMenu">
                    <button onclick="location.href='edit-organization.php'" class="active"> EDIT ORGANIZATION DETAILS </button>
                    <button class="dark-mode-btn"> DARK/LIGHT MODE </button>
                    <button onclick="window.location.href='../login-side-main/login.html'"> LOG OUT </button>
                </div>
            </div>
        </div>
    </nav>

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
