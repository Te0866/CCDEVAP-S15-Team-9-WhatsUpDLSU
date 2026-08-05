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

<script>
const modalOverlay = document.getElementById('modalOverlay');
const modalMessage = document.getElementById('modalMessage');
const modalActions = document.getElementById('modalActions');

function closeModal() {
    modalOverlay.classList.remove('show');
}

function openModal(message, buttons) {
    modalMessage.textContent = message;
    modalActions.innerHTML = '';

    buttons.forEach(btn => {
        const b = document.createElement('button');
        b.textContent = btn.label;
        b.className = btn.className || 'modal-btn-secondary';
        b.addEventListener('click', () => {
            closeModal();
            if (btn.onClick) btn.onClick();
        });
        modalActions.appendChild(b);
    });

    modalOverlay.classList.add('show');
}

// clicking the dark backdrop dismisses the modal like a Cancel
modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) closeModal();
});

function showAlert(message) {
    openModal(message, [
        { label: 'OK', className: 'modal-btn-primary' }
    ]);
}

function showConfirm(message, onConfirm) {
    openModal(message, [
        { label: 'Cancel', className: 'modal-btn-secondary' },
        { label: 'Delete', className: 'modal-btn-danger', onClick: onConfirm }
    ]);
}

// ---------- Comment cards ----------
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
                    showAlert(data.error || 'Failed to update comment.');
                }
            } catch (err) {
                showAlert('Something went wrong. Please try again.');
            }
        });
    });

    deleteBtn.addEventListener('click', () => {
        showConfirm('Delete this comment? This cannot be undone.', async () => {
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
                    showAlert(data.error || 'Failed to delete comment.');
                }
            } catch (err) {
                showAlert('Something went wrong. Please try again.');
            }
        });
    });
});
</script>

</body>
</html>
