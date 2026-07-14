const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (event) => {
    event.stopPropagation();
    dropdownMenu.classList.toggle("show");

});

document.addEventListener("click", (event) => {
    if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)){
        dropdownMenu.classList.remove("show");
    }
});



const container = document.getElementById("interestedEventsContainer");
const interestedEvents = JSON.parse(localStorage.getItem("interestedEvents")) || [];
container.innerHTML = "";

if (interestedEvents.length === 0) {
    container.innerHTML = `
        <div class="event-card">
            <h3>No Events Yet</h3>
            <p>Add events from Events page</p>
        </div>
    `;
} else {
    interestedEvents.forEach(event => {

        container.innerHTML += `
            <div class="event-card">
                <h3>${event.title}</h3>
                <p>Interested Event</p>
            </div>
        `;
    });
}

const chartCanvas = document.getElementById("studentChart");

fetch("get-category-stats.php")
.then(res => res.json())
.then(data => {

    const labels = data.map(item => item.CATEGORY);
    const values = data.map(item => item.total);

    new Chart(chartCanvas, {
        type: "pie",
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    "#3498db",
                    "#9b59b6",
                    "#f1c40f"
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });

});

const popularCanvas = document.getElementById("popularChart");

new Chart(popularCanvas, {
    type: "bar",
    data: {
        labels: [
            "Hackathon 2026",
            "Career Fair",
            "Anime Convention",
            "Research Expo",
            "Leadership Seminar"
        ],
        datasets: [{
            label: "Interested Students",
            data: [125, 98, 81, 67, 54],
            backgroundColor: [
                "#087f5b",
                "#1fa67a",
                "#39b88c",
                "#63c9a7",
                "#8edcc2"
            ],
            borderRadius: 8
        }]
    },
    options: {
        indexAxis: "y",
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                display: false
            },
            title: {
                display: false
            }
        },

        scales: {
            x: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: "Interested Students"
                }
            },
            y: {
                title: {
                    display: false
                }
            }
        }
    }
});
