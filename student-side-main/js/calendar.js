let eventsByDate = {};

function formatTime(time24) {
    const [hour, minute] = time24.split(':');
    const h = parseInt(hour);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const displayHour = h % 12 === 0 ? 12 : h % 12;
    return `${displayHour}:${minute} ${ampm}`;
}

function categoryToClassName(category) {
    if (category === "ACADEMIC") return "green";
    if (category === "NON-ACADEMIC") return "yellow";
    if (category === "CAREER") return "blue";
    return "";
}

function categoryToDisplayName(category) {
    if (category === "ACADEMIC") return "Academic";
    if (category === "NON-ACADEMIC") return "Non-Academic";
    if (category === "CAREER") return "Career";
    return category;
}

fetch('get-events.php')
    .then(res => res.json())
    .then(data => {
        eventsByDate = {};
        data.forEach(event => {
            if (!eventsByDate[event.date]) {
                eventsByDate[event.date] = [];
            }
            eventsByDate[event.date].push(event);
        });
        renderCalendar();
    })
    .catch(err => {
        console.error('Failed to load events:', err);
        renderCalendar(); // still render an empty calendar rather than nothing
    });

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


const grid = document.getElementById("calendarGrid");
const monthTitle = document.getElementById("monthTitle");
const today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();

const months = [
    "January","February","March",
    "April","May","June",
    "July","August","September",
    "October","November","December"
];

function renderCalendar(){

    grid.innerHTML = "";

    monthTitle.textContent =
        `${months[currentMonth]} ${currentYear}`;

    const daysHeader = ["Su","Mo","Tu","We","Th","Fr","Sa"];

    daysHeader.forEach((day,index)=>{

        const div = document.createElement("div");
        div.classList.add("day-header");

        if(index===0){
            div.classList.add("sunday");
        }

        div.textContent = day;
        grid.appendChild(div);

    });

    const firstDay =
        new Date(currentYear,currentMonth,1).getDay();

    const daysInMonth =
        new Date(currentYear,currentMonth+1,0).getDate();

    for(let i=0;i<firstDay;i++){

        const blank=document.createElement("div");
        grid.appendChild(blank);

    }

    for(let day=1;day<=daysInMonth;day++){

        const cell=document.createElement("div");
        cell.classList.add("day-cell");

        if(
            day===today.getDate() &&
            currentMonth===today.getMonth() &&
            currentYear===today.getFullYear()
        ){
            cell.classList.add("today");
        }

        cell.innerHTML=`<div class="day-number">${day}</div>`;

        // Show events only in the current month/year
        const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        if (eventsByDate[dateKey]) {
            eventsByDate[dateKey].forEach(event => {
                const dot = document.createElement("div");
                dot.classList.add("event-dot");
                dot.classList.add(categoryToClassName(event.category));
                cell.appendChild(dot);
            });
        }

        cell.addEventListener("click",()=>openModal(day));

        grid.appendChild(cell);

    }

}
const modal = document.getElementById("eventModal");
const modalDate = document.getElementById("modalDate");
const modalEvents = document.getElementById("modalEvents");
const closeBtn = document.querySelector(".close-btn");

function openModal(day){

    modal.classList.add("show");

    modalDate.textContent =`${months[currentMonth]} ${day}, ${currentYear}`;

    modalEvents.innerHTML = "";

    const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const dayEvents = eventsByDate[dateKey];

    if (!dayEvents || dayEvents.length === 0) {
        modalEvents.innerHTML = "<p>No events scheduled.</p>";
        return;
    }

    dayEvents.forEach(event => {
        modalEvents.innerHTML += `
            <div class="event-card">
                <h4>${event.title}</h4>
                <p><strong>Category:</strong> ${categoryToDisplayName(event.category)}</p>
                <p><strong>Time:</strong> ${formatTime(event.startTime)}</p>
                <p><strong>Location:</strong> ${event.location}</p>
            </div>
        `;
    });

}

document.getElementById("prevMonth").onclick = () => {
    modal.classList.remove("show");

    currentMonth--;

    if(currentMonth < 0){
        currentMonth = 11;
        currentYear--;
    }

    renderCalendar();
};

document.getElementById("nextMonth").onclick = () => {
    modal.classList.remove("show");

    currentMonth++;

    if(currentMonth > 11){
        currentMonth = 0;
        currentYear++;
    }

    renderCalendar();
};

closeBtn.onclick = () => {
    modal.classList.remove("show");
};

window.onclick = (e) => {
    if (e.target === modal) {
        modal.classList.remove("show");
    }
};
