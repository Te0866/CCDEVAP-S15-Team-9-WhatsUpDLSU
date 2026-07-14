<section class="banner">
    <h1>Hi <?php echo htmlspecialchars($user['USER_NAME']); ?>, Discover What's Happening at DLSU</h1>
    <p>Stay updated with university events, organization activities, workshops, seminars, and campus announcements.</p>
</section>

<section class="chart-container pie-chart">
    <h2 class="chart-title">Distribution of Event Categories</h2>
    <canvas id="studentChart"></canvas>
</section>

<section class="chart-container bar-chart">
    <h2 class="chart-title">Most Popular Events</h2>
    <canvas id="popularChart"></canvas>
</section>

<section class="category-box">
    <h2>Browse by Category</h2>
    <div class="category-item" onclick="location.href='?page=events&category=ACADEMIC'">
        <span class="color academic"></span> Academic
    </div>
    <div class="category-item" onclick="location.href='?page=events&category=NON-ACADEMIC'">
        <span class="color nonacademic"></span> Non-academic
    </div>
    <div class="category-item" onclick="location.href='?page=events&category=CAREER'">
        <span class="color career"></span> Career
    </div>
</section>

<section class="carousel-section">
    <h2>Interested Events!</h2>
    <div class="carousel">
        <button id="prevBtn" class="arrow">&lt;</button>
        <div id="interestedEventsContainer">
            <?php if (empty($interestedEvents)): ?>
                <div class="event-card">
                    <h3>No Events Yet</h3>
                    <p>Add events from the Events page</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($interestedEvents, 0, 2) as $event): ?>
                    <div class="event-card" onclick="location.href='?page=event&id=<?php echo $event['EVENT_ID']; ?>'">
                        <h3><?php echo htmlspecialchars($event['TITLE']); ?></h3>
                        <p><?php echo htmlspecialchars($event['CATEGORY']); ?></p>
                        <small><?php echo htmlspecialchars($event['DATE']); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button id="nextBtn" class="arrow">&gt;</button>
    </div>
</section>

<script>
const categoryStats = <?php echo json_encode($categoryStats); ?>;
const popularEvents = <?php echo json_encode($popularEvents); ?>;
const interestedEvents = <?php echo json_encode($interestedEvents); ?>;
</script>
