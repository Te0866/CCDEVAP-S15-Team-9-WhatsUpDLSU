<?php
// Available via extract() in BaseController::render():
// $user, $comments, $profilePath, $activeTab
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Comments - WhatsUpDLSU</title>
<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; }
    .container { max-width: 700px; margin: 30px auto; padding: 0 15px; }
    h1 { font-size: 22px; margin-bottom: 20px; }
    .comment-card {
        background: #fff; border-radius: 8px; padding: 14px 16px;
        margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .comment-meta { font-size: 12px; color: #777; margin-bottom: 6px; }
    .comment-meta a { color: #007bff; text-decoration: none; }
    .comment-text { font-size: 15px; white-space: pre-wrap; margin-bottom: 8px; }
    .comment-actions button {
        border: none; background: none; cursor: pointer; font-size: 13px;
        color: #007bff; margin-right: 12px; padding: 0;
    }
    .comment-actions button.delete-btn { color: #d9534f; }
    .empty-state { color: #777; text-align: center; margin-top: 40px; }
    textarea.edit-box { width: 100%; box-sizing: border-box; padding: 6px; font-size: 14px; }
</style>
</head>
<body>

<?php include __DIR__ . "/partials/navbar.view.php"; ?>

<div class="container">
    <h1>My Comments</h1>

    <?php if (empty($comments)): ?>
        <p class="empty-state">You haven't posted any comments yet.</p>
    <?php else: ?>
        <?php foreach ($comments as $c): ?>
            <div class="comment-card" data-comment-id="<?php echo (int)$c['COMMENT_ID']; ?>">
                <div class="comment-meta">
                    Posted <?php echo $c['IS_ANONYMOUS'] ? 'anonymously ' : ''; ?>on
                    <a href="events.php?id=<?php echo (int)$c['EVENT_ID']; ?>">event #<?php echo (int)$c['EVENT_ID']; ?></a>
                    <!-- adjust the href above if events.php uses a different query param -->
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

<script>
document.querySelectorAll('.comment-card').forEach(card => {
    const commentId = card.dataset.commentId;
    const textEl = card.querySelector('.comment-text');
    const editBtn = card.querySelector('.edit-btn');
    const deleteBtn = card.querySelector('.delete-btn');

    editBtn.addEventListener('click', () => {
        if (card.querySelector('.edit-box')) return; // already editing

        const currentText = textEl.textContent;
        const textarea = document.createElement('textarea');
        textarea.className = 'edit-box';
        textarea.value = currentText;

        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Save';
        saveBtn.style.marginTop = '6px';

        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.style.marginLeft = '8px';

        textEl.replaceWith(textarea);
        textarea.insertAdjacentElement('afterend', saveBtn);
        saveBtn.insertAdjacentElement('afterend', cancelBtn);
        editBtn.style.display = 'none';
        deleteBtn.style.display = 'none';

        cancelBtn.addEventListener('click', () => {
            textarea.replaceWith(textEl);
            saveBtn.remove();
            cancelBtn.remove();
            editBtn.style.display = '';
            deleteBtn.style.display = '';
        });

        saveBtn.addEventListener('click', async () => {
            const newText = textarea.value.trim();
            if (newText === '') return;

            try {
                const res = await fetch('edit-comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ comment_id: commentId, text: newText })
                });
                const data = await res.json();
                if (data.success) {
                    textEl.textContent = data.text;
                    textarea.replaceWith(textEl);
                    saveBtn.remove();
                    cancelBtn.remove();
                    editBtn.style.display = '';
                    deleteBtn.style.display = '';
                } else {
                    alert(data.error || 'Failed to update comment.');
                }
            } catch (err) {
                alert('Something went wrong. Please try again.');
            }
        });
    });

    deleteBtn.addEventListener('click', async () => {
        if (!confirm('Delete this comment?')) return;

        try {
            const res = await fetch('delete-comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ comment_id: commentId })
            });
            const data = await res.json();
            if (data.success) {
                card.remove();
            } else {
                alert(data.error || 'Failed to delete comment.');
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
        }
    });
});
</script>

</body>
</html>
