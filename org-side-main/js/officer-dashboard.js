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

function sum(arr) {
    return arr.reduce((total, n) => total + n, 0);
}

function markEmptyIfNoData(canvasEl, emptyEl, hasData) {
    if (!hasData) {
        canvasEl.classList.add("hide");
        emptyEl.classList.add("show");
    }
}

const statusCtx = document.getElementById("statusChart");

new Chart(statusCtx, {
    type: "pie",
    data: {
        labels: ["Approved", "Pending", "Rejected"],
        datasets: [{
            data: [approvedCount, pendingCount, rejectedCount],
            backgroundColor: [STATUS_COLORS.APPROVED, STATUS_COLORS.PENDING, STATUS_COLORS.REJECTED],
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
markEmptyIfNoData(statusCtx, document.getElementById("statusChartEmpty"), sum([approvedCount, pendingCount, rejectedCount]) > 0);

const categoryCtx = document.getElementById("categoryChart");

new Chart(categoryCtx, {
    type: "bar",
    data: {
        labels: ["Academic", "Non-academic", "Career"],
        datasets: [{
            data: [academicCount, nonAcademicCount, careerCount],
            backgroundColor: [CATEGORY_COLORS.ACADEMIC, CATEGORY_COLORS["NON-ACADEMIC"], CATEGORY_COLORS.CAREER],
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
markEmptyIfNoData(categoryCtx, document.getElementById("categoryChartEmpty"), sum([academicCount, nonAcademicCount, careerCount]) > 0);

const locationCtx = document.getElementById("locationChart");

new Chart(locationCtx, {
    type: "bar",
    data: {
        labels: locationLabels,
        datasets: [{
            data: locationData,
            backgroundColor: "#087f5b",
            borderRadius: 6,
            maxBarThickness: 32
        }]
    },
    options: {
        indexAxis: "y",
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: "#eef2ef" }
            },
            y: {
                grid: { display: false }
            }
        }
    }
});
markEmptyIfNoData(locationCtx, document.getElementById("locationChartEmpty"), locationData.length > 0 && sum(locationData) > 0);

const interestCtx = document.getElementById("interestChart");

new Chart(interestCtx, {
    type: "bar",
    data: {
        labels: interestLabels,
        datasets: [{
            data: interestData,
            backgroundColor: "#2563a6",
            borderRadius: 6,
            maxBarThickness: 32
        }]
    },
    options: {
        indexAxis: "y",
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: "#eef2ef" }
            },
            y: {
                grid: { display: false }
            }
        }
    }
});
markEmptyIfNoData(interestCtx, document.getElementById("interestChartEmpty"), interestData.length > 0 && sum(interestData) > 0);

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
