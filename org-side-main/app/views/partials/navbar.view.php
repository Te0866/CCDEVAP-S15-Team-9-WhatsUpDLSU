<nav class="navbar">
    <div class="nav-left">
        <div><img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo"></div>
        <span class="logo-text"> WhatsUpDLSU </span>
    </div>

    <div class="nav-right">
        <div class="nav-links">
            <a href="officer-dashboard.php" class="nav-tab<?php echo $activeNav === 'home' ? ' active' : ''; ?>"> Home </a>
            <a href="create.php" class="nav-tab<?php echo $activeNav === 'create' ? ' active' : ''; ?>"> Create </a>
            <a href="manage.php" class="nav-tab<?php echo $activeNav === 'manage' ? ' active' : ''; ?>"> Manage </a>
        </div>

        <div class="profile-section">
            <button class="profile-btn" id="profileBtn">
                <img src="<?php echo htmlspecialchars($profilePath); ?>" alt="Profile" class="profile-pic">
            </button>
            <div class="dropdown-menu" id="dropdownMenu">
                <?php if ($activeNav === 'organization'): ?>
                <button onclick="location.href='edit-organization.php'" class="active"> EDIT ORGANIZATION DETAILS </button>
                <?php else: ?>
                <button onclick="location.href='edit-organization.php'"> EDIT ORGANIZATION DETAILS </button>
                <?php endif; ?>
                <button class="dark-mode-btn"> DARK/LIGHT MODE </button>
                <button onclick="window.location.href='../login-side-main/login.html'"> LOG OUT </button>
            </div>
        </div>
    </div>
</nav>
