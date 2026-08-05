<?php
// Available via extract() in BaseController::render():
// $user, $comments, $profilePath, $activeTab
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Comments - WhatsUpDLSU</title>
<link rel="stylesheet" href="../assets/styles/student/darkmode.css">
<link rel="stylesheet" href="../assets/styles/student/my-comments.css">
</head>
<body class="s-my-comments">

<?php include __DIR__ . "/partials/navbar.view.php"; ?>

<div class="page-container">
    <h1 class="page-title">My Comments</h1>

    <?php if (empty($comments)): ?>
        <p class="empty-state">You haven't posted any comments yet.</p>
    <?php else: ?>
        <?php foreach ($comments as $c): ?>
            <div class="comment-card" data-comment-id="<?php echo (int)$c['COMMENT_ID']; ?>">
                <div class="comment-meta">
                    Posted on
                    <?php echo htmlspecialchars($c['EVENT_TITLE']); ?></a>
                    <?php if ($c['IS_ANONYMOUS']): ?>
                        <span class="anon-badge">Posted anonymously</span>
                    <?php endif; ?>
                </div>
                <div class="comment-text"><?php echo htmlspecialchars($c['TEXT']); ?></div>
                <div class="comment-actions">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <p class="modal-message" id="modalMessage"></p>
        <div class="modal-actions" id="modalActions"></div>
    </div>
</div>

    <script src="../assets/scripts/student/darkmode.js"></script>
    

</body>
</html>
