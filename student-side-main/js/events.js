const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");
profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", () => {
    dropdownMenu.classList.remove("show");
});


let eventsData = [];
let selectedEvent = null;

fetch('events.json').then(res => res.json()).then(data => {
        eventsData = data;

        const params = new URLSearchParams(window.location.search);
        const categoryParam = params.get('category');

        if (categoryParam) {
            const categorySelect = document.querySelectorAll('.filter-box')[1]; 
            categorySelect.value = categoryParam;

            const filtered = eventsData.filter(e => e.category === categoryParam);
            renderSidebar(filtered);
            if (filtered.length > 0) {
                selectedEvent = filtered[0];
                showEventDetail(selectedEvent);
            }
        } else {
            renderSidebar(eventsData);
            if (eventsData.length > 0) {
                selectedEvent = eventsData[0];
                showEventDetail(selectedEvent);
            }
        }
    });

function renderSidebar(events) {
    const sidebar = document.getElementById('eventSidebar');
    sidebar.innerHTML = '';

    events.forEach((event, index) => {
        const btn = document.createElement('button');
        btn.className = 'event-item';
        if (index === 0) btn.classList.add('active');
        btn.textContent = event.title;

        btn.addEventListener('click', () => {
            document.querySelectorAll('.event-item').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedEvent = event;
            showEventDetail(event);
        });

        sidebar.appendChild(btn);
    });
}

function showEventDetail(event) {
    document.getElementById('eventTitle').textContent = event.title;
    document.getElementById('category').textContent = event.category;
    document.getElementById('duration').textContent =
        formatTime(event.startTime) + ' - ' + formatTime(event.endTime);
    document.getElementById('venue').textContent = event.venue;
    document.getElementById('status').textContent = event.status;
    document.getElementById('registration').textContent = event.registration;
    document.getElementById('organizer').textContent = event.organizer;
    document.getElementById('description').textContent = event.description;

    const imageBoxes = document.querySelectorAll('.image-box');
    imageBoxes.forEach((box, i) => {
        if (event.images[i]) {
            box.innerHTML = `<img src="${event.images[i]}" alt="${event.title} image ${i+1}">`;
        } else {
            box.innerHTML = '';
        }
    });
}

function formatTime(time24) {
    const [hour, minute] = time24.split(':');
    const h = parseInt(hour);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const displayHour = h % 12 === 0 ? 12 : h % 12;
    return `${displayHour}:${minute} ${ampm}`;
}

document.getElementById("interestedBtn").addEventListener("click", () => {
    const interestedEvents = JSON.parse(localStorage.getItem("interestedEvents")) || [];
    const eventData = { title: selectedEvent.title };

    const exists = interestedEvents.some(event => event.title === eventData.title);
    if (!exists) {
        interestedEvents.push(eventData);
        localStorage.setItem("interestedEvents", JSON.stringify(interestedEvents));
        alert("Added to Interested Events!");
    } else {
        alert("Already added!");
    }
});
