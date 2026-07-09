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
            data: [12, 3, 5],
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
            data: [11, 4, 9],
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
