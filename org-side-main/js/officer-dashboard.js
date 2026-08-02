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

const categoryApprovalCtx = document.getElementById("categoryApprovalChart");

new Chart(categoryApprovalCtx, {
    type: "bar",
    data: {
        labels: categoryLabels,
        datasets: [
            {
                label: "Approved",
                data: categoryApprovedData,
                backgroundColor: STATUS_COLORS.APPROVED,
                borderRadius: 4,
                maxBarThickness: 55
            },
            {
                label: "Pending",
                data: categoryPendingData,
                backgroundColor: STATUS_COLORS.PENDING,
                borderRadius: 4,
                maxBarThickness: 55
            },
            {
                label: "Rejected",
                data: categoryRejectedData,
                backgroundColor: STATUS_COLORS.REJECTED,
                borderRadius: 4,
                maxBarThickness: 55
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                stacked: true,
                grid: { display: false }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                grid: { color: "#eef2ef" }
            }
        }
    }
});
markEmptyIfNoData(
    categoryApprovalCtx,
    document.getElementById("categoryApprovalChartEmpty"),
    sum([...categoryApprovedData, ...categoryPendingData, ...categoryRejectedData]) > 0
);

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

const scatterCtx = document.getElementById("scatterChart");

const scatterColors = scatterPoints.map((p) => CATEGORY_COLORS[p.category] || "#8a9791");

new Chart(scatterCtx, {
    type: "scatter",
    data: {
        datasets: [{
            data: scatterPoints,
            backgroundColor: scatterColors,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: (ctx) => {
                        const point = ctx.raw;
                        return `${point.title}: ${point.y} interested`;
                    }
                }
            }
        },
        scales: {
            x: {
                type: "category",
                labels: scatterDateLabels,
                title: { display: true, text: "Event date", color: "#8a9791", font: { size: 12 } },
                grid: { color: "#eef2ef" }
            },
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                title: { display: true, text: "Students interested", color: "#8a9791", font: { size: 12 } },
                grid: { color: "#eef2ef" }
            }
        }
    }
});
markEmptyIfNoData(scatterCtx, document.getElementById("scatterChartEmpty"), scatterPoints.length > 0);

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
