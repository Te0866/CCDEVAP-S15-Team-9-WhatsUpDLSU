<?php
// Expects $user, $profilePath, $activeTab in scope (passed by
// EditProfileController::render() via extract()).
//
// NOTE: unlike the original, the password field is intentionally left
// BLANK rather than pre-filled with $user['PASSWORD']. That column now
// holds a bcrypt hash (see User::updateProfile()), not a plaintext
// password, so displaying it would show gibberish. Leaving a new
// password blank on submit now means "keep my current password" —
// see ProfileController::update().
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

        <?php require __DIR__ . "/partials/navbar.view.php"; ?>

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
                        <input type="file" id="profileImage" name="profileImage" accept="image/*" hidden>
                        <button type="button"
                                class="upload-btn"
                                onclick="document.getElementById('profileImage').click()">
                            Upload Image
                        </button>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <div class="input-wrapper">
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['USER_NAME']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                            <button type="button" id="togglePassword" class="show-password-btn">
                                Show
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <button class="update-btn" id="updateBtn"> UPDATE </button>
        </main>
        <script src="js/edit-profile.js"></script>
        <script src="js/darkmode.js"></script>
    </body>
</html>
