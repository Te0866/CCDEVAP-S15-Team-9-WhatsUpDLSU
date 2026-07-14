<div class="event-detail">
    <a href="?page=events" class="back-btn">← Back to Events</a>
    
    <?php if ($event['BANNER_IMAGE']): ?>
        <img src="<?php echo htmlspecialchars($event['BANNER_IMAGE']); ?>" alt="<?php echo htmlspecialchars($event['TITLE']); ?>" class="event-banner">
    <?php endif; ?>
    
    <h1><?php echo htmlspecialchars($event['TITLE']); ?></h1>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($event['CATEGORY']); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['DATE']); ?></p>
    <p><?php echo htmlspecialchars($event['DESCRIPTION'] ?? ''); ?></p>
    
    <button onclick="toggleInterest(<?php echo $event['EVENT_ID']; ?>)" id="interestBtn">
        <?php echo $isInterested ? '❤️ Interested' : '🤍 Interested'; ?>
    </button>
</div>

<script>
function toggleInterest(eventId) {
    fetch('?page=api-toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'event_id=' + eventId
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById('interestBtn');
            btn.textContent = data.action === 'added' ? '❤️ Interested' : '🤍 Interested';
        }
    })
    .catch(err => console.error(err));
}
</script>
