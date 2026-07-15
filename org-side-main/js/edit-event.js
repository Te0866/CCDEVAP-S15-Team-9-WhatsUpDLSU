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

document.getElementById("submitBtn").addEventListener("click", () => {
    alert("Event updated successfully!");
});

document.getElementById("deleteBtn").addEventListener("click", () => {

    const confirmed = confirm("Are you sure you want to delete this event?");

    if (confirmed) {
        alert("Event deleted successfully.");
        window.location.href = "manage.html";
    }

});