const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", (e) => {
    if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove("show");
    }
});

const uploadBox = document.querySelector(".upload-box");
const fileInput = document.getElementById("eventImage");
const uploadIcon = document.querySelector(".upload-icon");
const removeImageFlag = document.getElementById("removeImageFlag");

uploadBox.addEventListener("click", () => {
    fileInput.click();
});

function attachRemoveHandler(btn, chip, isExisting) {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        chip.remove();
        fileInput.value = "";
        if (isExisting && removeImageFlag) {
            removeImageFlag.value = "1";
        }
    });
}

const existingChip = document.getElementById("existingImageChip");
if (existingChip) {
    const existingRemoveBtn = existingChip.querySelector("#removeFile");
    if (existingRemoveBtn) {
        attachRemoveHandler(existingRemoveBtn, existingChip, true);
    }
}

fileInput.addEventListener("change", () => {
    const oldChip = uploadBox.querySelector(".file-chip");
    if (oldChip) oldChip.remove();

    if (fileInput.files.length > 0) {
        if (removeImageFlag) {
            removeImageFlag.value = "0"; 
        }

        const chip = document.createElement("div");
        chip.className = "file-chip";

        const nameSpan = document.createElement("span");
        nameSpan.textContent = fileInput.files[0].name;

        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.textContent = "×";

        chip.appendChild(nameSpan);
        chip.appendChild(removeBtn);
        uploadBox.insertBefore(chip, uploadIcon);

        attachRemoveHandler(removeBtn, chip, false);
    }
});

document.getElementById("deleteBtn").addEventListener("click", async () => {
    const confirmed = await showConfirmModal(
        "Are you sure you want to delete this event?",
        { confirmText: "Delete", cancelText: "Cancel", danger: true, title: "Delete event" }
    );

    if (!confirmed) return;

    const eventIdInput = document.querySelector('input[name="event_id"]');
    const eventId = eventIdInput ? eventIdInput.value : "";

    try {
        const response = await fetch("delete-event-process.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "event_id=" + encodeURIComponent(eventId),
        });

        let data;
        try {
            data = await response.json();
        } catch {
            data = null;
        }

        if (!response.ok || !data || !data.success) {
            await showModal(
                (data && data.error) || "Something went wrong while deleting the event.",
                { type: "error" }
            );
            return;
        }

        window.location.href = "manage.php?deleted=1";
    } catch (err) {
        await showModal("Something went wrong while deleting the event.", { type: "error" });
    }
});

const commentsList = document.getElementById("commentsList");

function renderComments(comments) {
    commentsList.innerHTML = "";

    if (comments.length === 0) {
        const empty = document.createElement("li");
        empty.className = "comments-empty";
        empty.textContent = "No comments yet on this event.";
        commentsList.appendChild(empty);
        return;
    }

    comments.forEach((comment) => {
        const item = document.createElement("li");
        item.className = "comment-item";
        item.dataset.commentId = comment.id;

        const textWrap = document.createElement("div");
        textWrap.className = "comment-text-wrap";

        const author = document.createElement("span");
        author.className = "comment-author";
        author.textContent = comment.author;

        const text = document.createElement("p");
        text.className = "comment-text";
        text.textContent = comment.text;

        textWrap.appendChild(author);
        textWrap.appendChild(text);

        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className = "comment-delete-btn";
        deleteBtn.textContent = "Delete";
        deleteBtn.addEventListener("click", () => deleteComment(comment.id, item));

        item.appendChild(textWrap);
        item.appendChild(deleteBtn);
        commentsList.appendChild(item);
    });
}

async function loadComments() {
    try {
        const response = await fetch("get-event-comments.php?event_id=" + encodeURIComponent(currentEventId));
        const data = await response.json();

        if (!response.ok || !data.success) {
            commentsList.innerHTML = "<li class=\"comments-empty\">Couldn't load comments.</li>";
            return;
        }

        renderComments(data.comments);
    } catch (err) {
        commentsList.innerHTML = "<li class=\"comments-empty\">Couldn't load comments.</li>";
    }
}

async function deleteComment(commentId, itemEl) {
    const confirmed = await showConfirmModal(
        "Are you sure you want to delete this comment?",
        { confirmText: "Delete", cancelText: "Cancel", danger: true, title: "Delete comment" }
    );

    if (!confirmed) return;

    try {
        const response = await fetch("delete-comment-process.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ comment_id: commentId }),
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            await showModal((data && data.error) || "Something went wrong while deleting the comment.", { type: "error" });
            return;
        }

        itemEl.remove();
        if (!commentsList.querySelector(".comment-item")) {
            renderComments([]);
        }
    } catch (err) {
        await showModal("Something went wrong while deleting the comment.", { type: "error" });
    }
}

loadComments();
