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

uploadBox.addEventListener("click", () => {
    fileInput.click();
});

const removeFile = document.getElementById("removeFile");

if (removeFile) {
    removeFile.addEventListener("click", (e) => {
        e.stopPropagation();

        const fileChip = document.querySelector(".file-chip");

        if (fileChip) {
            fileChip.remove();
        }
    });
}

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