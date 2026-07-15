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

// The success modal now appears on manage.php after the server actually
// confirms the event was updated (see manage.js), instead of alerting here
// before the form has even been submitted.

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

        // Redirect first, then flash the success modal on manage.php once the
        // server has actually confirmed the delete (same pattern used for
        // "updated=1" after a successful update).
        window.location.href = "manage.php?deleted=1";
    } catch (err) {
        await showModal("Something went wrong while deleting the event.", { type: "error" });
    }
});