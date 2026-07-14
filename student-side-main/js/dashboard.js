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
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

let events = [];
let currentIndex = 0;

function renderCarousel() {

    container.innerHTML = "";

    if (events.length === 0) {
        container.innerHTML = `
            <div class="event-card">
                <h3>No Events Yet</h3>
                <p>Add events from the Events page</p>
            </div>
        `;
        return;
    }

    for (let i = 0; i < 2; i++) {

        const index = currentIndex + i;

        if (index >= events.length) break;

        const event = events[index];

        container.innerHTML += `
            <div class="event-card" onclick="location.href='events.php?id=${event.id}'">
                <h3>${event.title}</h3>
                <p>${event.category}</p>
                <small>${event.date}</small>
            </div>
        `;
    }
}

fetch("get-interested-events.php")
    .then(res => res.json())
    .then(data => {
        events = data;
        renderCarousel();
    });
.catch(err => console.error(err));
nextBtn.addEventListener("click", () => {
    if (currentIndex + 2 < events.length) {
        currentIndex += 2;
        renderCarousel();
    }
});

prevBtn.addEventListener("click", () => {
    if (currentIndex - 2 >= 0) {
        currentIndex -= 2;
        renderCarousel();
    }
});
    

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

fetch("get-popular-events.php")
.then(res => res.json())
.then(data => {

    const labels = data.map(item => item.TITLE);
    const values = data.map(item => Number(item.interested));

    new Chart(popularCanvas, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Interested Students",
                data: values,
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

})
.catch(error => {
    console.error("Error loading popular events:", error);
});
