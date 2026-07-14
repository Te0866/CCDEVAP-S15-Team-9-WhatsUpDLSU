<div class="back-container">
    <button class="back-btn" onclick="window.location.href='?page=dashboard'">
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
                <button type="button" class="upload-btn" onclick="document.getElementById('profileImage').click()">
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
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['PASSWORD']); ?>">
                    <button type="button" id="togglePassword" class="show-password-btn">
                        Show
                    </button>
                </div>
            </div>
        </form>
    </div>
    <button class="update-btn" id="updateBtn">UPDATE</button>
</main>
