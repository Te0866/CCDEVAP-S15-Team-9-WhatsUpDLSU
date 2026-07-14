<?php
// this expects $activeTab and $profilePath to already be set by the including page
?>
<nav class="navbar">
    <div class="nav-left">
        <a href="dashboard.php" class="nav-left">
            <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
            <span class="logo-text">WhatsUpDLSU</span>
        </a>
    </div>

    <div class="nav-right">
        <div class="nav-links">
            <a href="?page=dashboard" class="nav-tab <?php echo ($activeTab ?? '') === 'home' ? 'active' : ''; ?>">Home</a>
            <a href="?page=events" class="nav-tab <?php echo ($activeTab ?? '') === 'events' ? 'active' : ''; ?>">Events</a>
            <a href="calendar.php" class="nav-tab <?php echo ($activeTab ?? '') === 'calendar' ? 'active' : ''; ?>">Calendar</a>
        </div>

        <div class="profile-section">
            <button class="profile-btn" id="profileBtn">
                <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile" class="profile-pic">
            </button>

            <div class="dropdown-menu" id="dropdownMenu">
                <button type="button" class="dark-mode-btn"> DARK/LIGHT MODE </button>
                <button onclick="window.location.href='edit-profile.php'"> EDIT USER DETAILS </button>
                <button onclick="window.location.href='../login-side-main/logout.php'"> LOG OUT </button>
            </div>
        </div>
    </div>
</nav>
