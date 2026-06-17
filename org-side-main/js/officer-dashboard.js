/* DROPDOWN */

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

/* Pie Chart */

const ctx = document.getElementById("dashboardChart");

new Chart(ctx, {
    type: "pie",
    data: {
        labels: ["Active Events", "Pending Approval", "Past Events"],
        datasets: [{
            data: [60,15,25],backgroundColor: ["#3498db", "#9b59b6","#f1c40f"]
        }]
    },

    options: {
        plugins: {
            legend: {
                display:false
            }
        }
    }
});