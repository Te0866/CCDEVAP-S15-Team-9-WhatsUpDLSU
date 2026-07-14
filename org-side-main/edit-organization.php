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
                <a href="manage.html" class="nav-tab"> Manage </a>
            </div>

            <div class="profile-section">
                <button class="profile-btn" id="profileBtn"> OrgName ▼ </button>
                <div class="dropdown-menu" id="dropdownMenu">
                    <button onclick="location.href='edit-organization.html'" class="active"> EDIT ORGANIZATION DETAILS </button>
                    <button class="dark-mode-btn"> DARK/LIGHT MODE </button>
                    <button onclick="window.location.href='../login-side-main/officer-login.html'"> LOG OUT </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="edit-page">

        <div class="header-row">

            <button class="back-btn" onclick="location.href='officer-dashboard.html'">
                ◀ Dashboard
            </button>

            <h1 class="page-title">Edit Organization Details</h1>

            <div class="header-spacer"></div>

        </div>

        <div class="form-card">

            <h3 class="section-heading">Account Information</h3>

            <div class="form-group">
                <label>Organization Name <span class="required-badge">required</span></label>
                <input type="text" value="SAMPLE DATA" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Username <span class="required-badge">required</span></label>
                    <input type="email" value="SAMPLE DATA@dlsu.edu.ph" required>
                </div>

                <div class="form-group">
                    <label>Password <span class="required-badge">required</span></label>
                    <input type="password" value="password123" required>
                </div>
            </div>

            <div class="button-group">
                <button class="update-btn" id="updateBtn"> Update Details </button>
            </div>

        </div>

    </main>

    <script src="js/edit-organization.js"></script>
    <script src="js/darkmode.js"></script>
</body>
</html>
