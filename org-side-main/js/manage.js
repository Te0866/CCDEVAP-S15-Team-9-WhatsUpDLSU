const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", () => {
    dropdownMenu.classList.remove("show");
});


document.querySelectorAll(".status-badge").forEach(badge => {
    const status = badge.textContent.trim().toLowerCase();

    badge.classList.remove("pending", "approved", "rejected");
    badge.classList.add(status);
});

const pending = document.querySelectorAll(".pending-card").length;
const approved = document.querySelectorAll(".approved-card").length;
const rejected = document.querySelectorAll(".rejected-card").length;

document.getElementById("pendingCount").textContent = pending;
document.getElementById("approvedCount").textContent = approved;
document.getElementById("rejectedCount").textContent = rejected;
