<?php

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WhatsUpDLSU - Events</title>
        <link rel="stylesheet" href="css/darkmode.css">
        <link rel="stylesheet" href="css/events.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>

        <?php require __DIR__ . "/partials/navbar.view.php"; ?>

        <div class="back-container">
            <button class="back-btn" onclick="window.location.href='dashboard.php'">
                Dashboard
            </button>
        </div>

        <section class="search-section">
            <input type="text" id="searchInput" placeholder="Search Events" class="search-input">
            <select id="statusFilter" class="filter-box">
                <option>All</option>
                <option>Upcoming</option>
                <option>Ongoing</option>
                <option>Ended</option>
            </select>
            <input type="date" id="dateFilter" class="filter-box">

            <select id="categoryFilter" class="filter-box">
                <option>All Event Status</option>
                <option>Academic</option>
                <option>Career</option>
                <option>Non-academic</option>
            </select>

            <select id="sortFilter" class="filter-box">
                <option>Newest</option>
                <option>Oldest</option>
            </select>
            <button id="clearFiltersBtn" class="filter-clear-btn">
                Clear
            </button>
        </section>

        <main class="events-layout">
            <aside class="event-sidebar" id="eventSidebar"></aside>
            <section class="event-detail">

                <div class="event-info">
                    <h1 id="eventTitle">Placeholder</h1>
                    <div class="details">
                        <p><strong>Category:</strong>
                            <span id="category">Placeholder</span>
                        </p>
                        <p><strong>Date:</strong>
                            <span id="eventDate">Placeholder</span>
                        </p>
                        <p><strong>Duration:</strong>
                            <span id="duration">Placeholder</span>
                        </p>

                        <p><strong>Venue:</strong>
                            <span id="venue">Placeholder</span>
                        </p>

                        <p><strong>Status:</strong>
                            <span id="status">Placeholder</span>
                        </p>

                        <p><strong>Registration:</strong>
                            <span id="registration">Placeholder</span>
                        </p>

                        <p><strong>Organizer:</strong>
                            <span id="organizer">Placeholder</span>
                        </p>

                    </div>

                    <div class="description">
                        <h3>Description:</h3>
                        <p id="description"> Placeholder </p>
                    </div>

                    <button id="interestedBtn" class="interested-btn"> Interested! </button>
                </div>

                <div class="image-column">
                    <div class="carousel image-carousel">
                        <div class="carousel-track" id="imageTrack"></div>
                            <button class="carousel-btn prev" id="imgPrev">&#10094;</button>
                            <button class="carousel-btn next" id="imgNext">&#10095;</button>
                            <div class="carousel-dots" id="imageDots"></div>
                        </div>

                    <div class="carousel comments-carousel">
                        <div class="carousel-track" id="commentsTrack"></div>
                        <div class="carousel-dots" id="commentsDots"></div>
                    </div>
                    <button id="postCommentBtn" class="post-comment-btn">Post Comment</button>
                </div>
            </section>

        </main>

        <div class="modal-overlay" id="commentModalOverlay">
            <div class="modal-box">
                <h3>Post a Comment</h3>
                <form id="commentForm">
                    <div class="commenter-row">
                        <span>Posting as: <strong id="commenterName">Student</strong></span>
                        <label class="anon-toggle">
                            <input type="checkbox" id="anonToggle">
                            Post Anonymously?
                        </label>
                    </div>

                    <label for="commentMessage">Message</label>
                    <textarea id="commentMessage" name="text" placeholder="Write your comment..." rows="4" required></textarea>

                    <div class="modal-actions">
                        <button type="button" id="cancelCommentBtn" class="modal-cancel-btn">Cancel</button>
                        <button type="submit" class="modal-submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="alertModal" class="modal-overlay">
            <div class="modal-box">
                <h3 id="alertTitle">Notice</h3>
                <p id="alertMessage"></p>

                <div class="modal-actions">
                    <button id="alertOkBtn" class="modal-submit-btn">OK</button>
                </div>
            </div>
        </div>

        <script>
            const currentUsername = <?php echo json_encode($user['USER_NAME']); ?>;
        </script>
        <script src="js/events.js"></script>
        <script src="js/darkmode.js"></script>
    </body>
</html>
