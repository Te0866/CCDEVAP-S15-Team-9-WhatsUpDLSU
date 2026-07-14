// Profile dropdown
const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

if (profileBtn) {
    profileBtn.addEventListener("click", (event) => {
        event.stopPropagation();
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", (event) => {
        if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show");
        }
    });
}

// Carousel
const container = document.getElementById("interestedEventsContainer");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

let events = [];
let currentIndex = 0;

if (typeof interestedEvents !== 'undefined' && interestedEvents.length > 0) {
    events = interestedEvents.map(e => ({
        id: e.EVENT_ID,
        title: e.TITLE,
        category: e.CATEGORY,
        date: e.DATE
    }));
    renderCarousel();
} else {
    fetch("?page=api-interested")
        .then(res => res.json())
        .then(data => { events = data; renderCarousel(); })
        .catch(err => console.error(err));
}

function renderCarousel() {
    if (!container) return;
    container.innerHTML = "";
    
    if (events.length === 0) {
        container.innerHTML = `<div class="event-card"><h3>No Events Yet</h3><p>Add events from the Events page</p></div>`;
        return;
    }
    
    for (let i = 0; i < 2; i++) {
        const index = currentIndex + i;
        if (index >= events.length) break;
        const event = events[index];
        container.innerHTML += `
            <div class="event-card" onclick="location.href='?page=event&id=${event.id}'">
                <h3>${event.title}</h3>
                <p>${event.category}</p>
                <small>${event.date}</small>
            </div>
        `;
    }
}

if (nextBtn) nextBtn.addEventListener("click", () => {
    if (currentIndex + 2 < events.length) { currentIndex += 2; renderCarousel(); }
});

if (prevBtn) prevBtn.addEventListener("click", () => {
    if (currentIndex - 2 >= 0) { currentIndex -= 2; renderCarousel(); }
});

// Charts
const chartCanvas = document.getElementById("studentChart");
if (chartCanvas && typeof categoryStats !== 'undefined') {
    new Chart(chartCanvas, {
        type: "pie",
        data: {
            labels: categoryStats.map(i => i.CATEGORY),
            datasets: [{ data: categoryStats.map(i => parseInt(i.total)), backgroundColor: ["#3498db", "#9b59b6", "#f1c40f"] }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: "bottom" } } }
    });
}

const popularCanvas = document.getElementById("popularChart");
if (popularCanvas && typeof popularEvents !== 'undefined') {
    new Chart(popularCanvas, {
        type: "bar",
        data: {
            labels: popularEvents.map(i => i.TITLE),
            datasets: [{ label: "Interested Students", data: popularEvents.map(i => parseInt(i.interested)), backgroundColor: ["#087f5b", "#1fa67a", "#39b88c", "#63c9a7", "#8edcc2"], borderRadius: 8 }]
        },
        options: { indexAxis: "y", responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, title: { display: true, text: "Interested Students" } } } }
    });
}
