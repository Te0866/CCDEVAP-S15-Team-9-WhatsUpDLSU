<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Create Event</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/create.css">
        <link rel="stylesheet" href="css/darkmode.css">
    </head>

    <body>
        <nav class="navbar">
            <div class="nav-left">

                <div>
                    <img class="logo" src="img/WhatsUpDLSULogo.png" alt="Logo">
                </div>

                <span class="logo-text"> WhatsUpDLSU </span>
            </div>

            <div class="nav-right">
                <div class="nav-links">
                    <a href="officer-dashboard.html" class="nav-tab"> Home </a>
                    <a href="create.html" class="nav-tab active"> Create </a>
                    <a href="manage.html" class="nav-tab"> Manage </a>
                </div>

                <div class="profile-section">
                    <button class="profile-btn" id="profileBtn"> 
                        OrgName ▼ 
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <button onclick="location.href='edit-organization.html'"> EDIT ORGANIZATION DETAILS </button>
                        <button class="dark-mode-btn"> DARK/LIGHT MODE </button>
                        <button onclick="window.location.href='../login-side-main/officer-login.html'"> LOG OUT </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="create-page">

            <div class="header-row">

                <button class="back-btn" onclick="location.href='officer-dashboard.html'">
                    ◀ Dashboard
                </button>

                <h1 class="page-title">Create Event</h1>

                <div class="header-spacer"></div>

            </div>

            <div class="form-card">

                <div class="form-grid">

                    <div class="form-left">

                        <h3 class="section-heading">Event Details</h3>

                        <div class="form-group">
                            <label>Event Name <span class="required-badge">required</span></label>
                            <input type="text" placeholder="Enter event name" required>
                        </div>

                        <div class="form-group">
                            <label>Category <span class="required-badge">required</span></label>
                            <select required>
                                <option value=""> Select Category </option>
                                <option> Academic </option>
                                <option> Non-Academic </option>
                                <option> Career </option>
                            </select>
                        </div>

                        <h3 class="section-heading">Where is this happening?</h3>

                        <div class="form-group">
                            <label>Location <span class="required-badge">required</span></label>
                            <select id="location" name="location" required>
                                <option value="">Select Location</option>

                                <option>Andrew Gonzalez Hall (AG)</option>
                                <option>Br. Connon Hall (CONNON)</option>
                                <option>Br. Andrew Gonzalez FSC Sports Complex</option>
                                <option>Br. Miguel Hall (MIGUEL)</option>
                                <option>Enrique M. Razon Sports Center</option>
                                <option>Faculty Center (FACULTY)</option>
                                <option>Gokongwei Hall (GOKONGWEI)</option>
                                <option>Henry Sy Sr. Hall (HSSH)</option>
                                <option>John Gokongwei Hall (JGH)</option>
                                <option>LS Building (LS)</option>
                                <option>Mutien Marie Hall</option>
                                <option>St. Joseph Hall (SJ)</option>
                                <option>St. La Salle Hall (LS)</option>
                                <option>STRC Building</option>
                                <option>William Hall (WILLIAM)</option>
                                <option>Yuchengco Hall (YUCH)</option>

                                <option>Amphitheater</option>
                                <option>Central Plaza</option>
                                <option>Cory Aquino Democratic Space</option>
                                <option>Henry Sy Grounds</option>
                                <option>University Mall</option>
                                <option>University Amphitheater</option>
                                <option>Velasco Roof Deck</option>
                                <option>Velasco Grounds</option>

                                <option>Online</option>
                                <option>Off-Campus</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Room / Venue <span class="optional-badge">optional</span></label>
                            <input type="text" id="room" name="room"
                                    placeholder="e.g. HSSH 807, G304, AVR, Multipurpose Hall">
                        </div>

                        <h3 class="section-heading">When is this happening?</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Date <span class="required-badge">required</span></label>
                                <input type="date" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Start Time <span class="required-badge">required</span></label>
                                <input type="time" required>
                            </div>

                            <div class="form-group">
                                <label>End Time <span class="required-badge">required</span></label>
                                <input type="time" required>
                            </div>
                        </div>

                    </div>

                    <div class="form-right">

                        <div class="form-group">
                            <label>Event Poster / Image <span class="optional-badge">optional</span></label>
                            <div class="upload-box">
                                <div class="upload-icon">📁</div>
                                <p>Click to upload or drag and drop</p>
                                <input type="file" id="eventImage">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description <span class="required-badge">required</span></label>
                            <textarea rows="8" placeholder="Enter event description" required></textarea>
                        </div>

                    </div>

                </div>

                <div class="button-group">
                    <button class="submit-btn" id="submitBtn"> Submit Event </button>
                    <button class="clear-btn" id="clearBtn"> Clear Form </button>
                </div>

            </div>

        </main>

        <script src="js/create.js"></script>
        <script src="js/darkmode.js"></script>

    </body>
</html>
