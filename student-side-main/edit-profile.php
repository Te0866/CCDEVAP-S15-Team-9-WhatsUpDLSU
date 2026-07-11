<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-side-main/login.html");
    exit;
}
$userId = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE USER_ID = ?");
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    die("User not found.");
}
require_once __DIR__ . "/../profile-picture.php";

$activeTab = '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit User Details</title>
        <link rel="stylesheet" href="css/darkmode.css">
        <link rel="stylesheet" href="css/edit-profile.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
        <?php require_once __DIR__ . "/../navbar.php"; ?>

<div class="back-container">
    <button class="back-btn" onclick="window.location.href='dashboard.php'">
        Back
    </button>
</div>
        <main class="profile-page">
            <div class="profile-container">
                <form id="profileForm">
    <div class="form-group profile-upload">
        <label>Profile Picture</label>
        <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile Picture" id="profilePreview" class="profile-preview">
        <input type="file" id="profileImage" accept="image/*" hidden>
        <button type="button"
                class="upload-btn"
                onclick="document.getElementById('profileImage').click()">
            Upload Image
        </button>
    </div>
    <div class="form-group">
        <label>Username</label>
        <div class="input-wrapper">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['USERNAME']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Password</label>
        <div class="input-wrapper">
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['PASSWORD']); ?>">
            <button type="button" id="togglePassword" class="show-password-btn">
                Show
            </button>
        </div>
    </div>
</form>
            </div>
            <button class="update-btn" id="updateBtn" > UPDATE </button>
        </main>
        <script src="js/edit-profile.js"></script>
        <script src="js/darkmode.js"></script>
    </body>
</html>
