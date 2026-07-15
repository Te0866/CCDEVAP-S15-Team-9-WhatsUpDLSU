const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get("created") === "1") {
    showModal("Event submitted successfully!").then(() => {});
    urlParams.delete("created");
    const newQuery = urlParams.toString();
    history.replaceState({}, "", window.location.pathname + (newQuery ? "?" + newQuery : ""));
}

const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", (e) => {
    if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
        dropdownMenu.classList.remove("show");
    }
});

const statusCtx = document.getElementById("statusChart");

new Chart(statusCtx, {
    type: "pie",
    data: {
        labels: ["Active", "Pending", "Past"],
        datasets: [{
            data: [activeCount, pendingCount, pastCount],
            backgroundColor: ["#3498db", "#9b59b6", "#f1c40f"],
            borderWidth: 0
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

const categoryCtx = document.getElementById("categoryChart");

new Chart(categoryCtx, {
    type: "bar",
    data: {
        labels: ["Academic", "Non-academic", "Career"],
        datasets: [{
            data: [academicCount, nonAcademicCount, careerCount],
            backgroundColor: "#2fb872",
            borderRadius: 6,
            maxBarThickness: 45
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 4 },
                grid: { color: "#eef2ef" }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

const eventGrid = document.getElementById("eventGrid");

function scrollEvents(direction) {
    const scrollAmount = 240;
    eventGrid.scrollBy({
        left: scrollAmount * direction,
        behavior: "smooth"
    });
}

const remarksModalOverlay = document.getElementById("remarksModalOverlay");
const remarksModalTitle = document.getElementById("remarksModalTitle");
const remarksModalStatus = document.getElementById("remarksModalStatus");
const remarksModalText = document.getElementById("remarksModalText");
const remarksModalClose = document.getElementById("remarksModalClose");

const statusLabels = {
    APPROVED: { text: "Approved", className: "status-approved" },
    PENDING: { text: "Pending", className: "status-pending" },
    REJECTED: { text: "Rejected", className: "status-rejected" }
};

document.querySelectorAll(".view-remarks-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const title = btn.dataset.title;
        const status = btn.dataset.status;
        const remarks = btn.dataset.remarks.trim();

        const statusInfo = statusLabels[status] || { text: status, className: "" };

        remarksModalTitle.textContent = title;
        remarksModalStatus.textContent = statusInfo.text;
        remarksModalStatus.className = "modal-status " + statusInfo.className;

        if (remarks === "") {
            remarksModalText.textContent = "No Remarks Added";
            remarksModalText.classList.add("no-remarks");
        } else {
            remarksModalText.textContent = remarks;
            remarksModalText.classList.remove("no-remarks");
        }

        remarksModalOverlay.classList.add("show");
    });
});

function closeRemarksModal() {
    remarksModalOverlay.classList.remove("show");
}

remarksModalClose.addEventListener("click", closeRemarksModal);

remarksModalOverlay.addEventListener("click", (e) => {
    if (e.target === remarksModalOverlay) {
        closeRemarksModal();
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        closeRemarksModal();
    }
});
