(function () {
    try {
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
        const fileInput = document.getElementById("eventImages") || document.getElementById("eventImage");
        const uploadIcon = document.querySelector(".upload-icon");
        const existingImagesInput = document.getElementById("existingImagesInput");
        const removeImageFlag = document.getElementById("removeImageFlag");
        const MAX_IMAGES = 4;
        let selectedFiles = [];
        let existingImages = [];

        if (existingImagesInput && existingImagesInput.value.trim() !== "") {
            existingImages = existingImagesInput.value.split(",")
                .map((s) => s.trim())
                .filter((s) => s !== "");
        }

        if (uploadBox && fileInput) {
            uploadBox.addEventListener("click", (e) => {
                if (e.target.closest(".file-chip button")) return;
                fileInput.click();
            });

            fileInput.addEventListener("change", () => {
                const incoming = Array.from(fileInput.files || []);
                const remaining = MAX_IMAGES - existingImages.length - selectedFiles.length;
                if (remaining <= 0) {
                    fileInput.value = "";
                    return;
                }
                selectedFiles = selectedFiles.concat(incoming.slice(0, remaining));
                if (removeImageFlag) {
                    removeImageFlag.value = "0";
                }
                renderChips();
                syncInputFiles();
            });
        }

        function syncExistingInput() {
            if (existingImagesInput) {
                existingImagesInput.value = existingImages.join(",");
            }
            if (removeImageFlag) {
                removeImageFlag.value = existingImages.length === 0 && selectedFiles.length === 0 ? "1" : "0";
            }
        }

        function renderChips() {
            if (!uploadBox) return;
            uploadBox.querySelectorAll(".file-chip, .upload-chips").forEach((el) => el.remove());

            const total = existingImages.length + selectedFiles.length;
            if (total === 0) return;

            const chipsWrap = document.createElement("div");
            chipsWrap.className = "upload-chips";

            existingImages.forEach((name, index) => {
                const chip = document.createElement("div");
                chip.className = "file-chip";

                const nameSpan = document.createElement("span");
                nameSpan.textContent = name;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "×";
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    existingImages.splice(index, 1);
                    syncExistingInput();
                    renderChips();
                });

                chip.appendChild(nameSpan);
                chip.appendChild(removeBtn);
                chipsWrap.appendChild(chip);
            });

            selectedFiles.forEach((file, index) => {
                const chip = document.createElement("div");
                chip.className = "file-chip";

                const nameSpan = document.createElement("span");
                nameSpan.textContent = file.name;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "×";
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    selectedFiles.splice(index, 1);
                    syncExistingInput();
                    renderChips();
                    syncInputFiles();
                });

                chip.appendChild(nameSpan);
                chip.appendChild(removeBtn);
                chipsWrap.appendChild(chip);
            });

            if (uploadIcon) {
                uploadBox.insertBefore(chipsWrap, uploadIcon);
            } else {
                uploadBox.appendChild(chipsWrap);
            }
        }

        function syncInputFiles() {
            if (!fileInput) return;
            const dt = new DataTransfer();
            selectedFiles.forEach((f) => dt.items.add(f));
            fileInput.files = dt.files;
        }

        // Show existing image chips on load
        renderChips();

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
    } catch (err) {
        console.error("[script.js] error in org/js/edit-event.js:", err);
    }
})();
