const events = {
    5: [
        {
            title: "Orientation",
            category: "Academic",
            time: "9:00 AM",
            location: "Henry Sy Hall"
        }
    ],

    10: [
        {
            title: "Club Fair",
            category: "Non-Academic",
            time: "1:00 PM",
            location: "SJ Walk"
        }
    ],

    14: [
        {
            title: "Programming Contest",
            category: "Academic",
            time: "10:00 AM",
            location: "Goks"
        },
        {
            title: "Free Pizza",
            category: "Non-Academic",
            time: "5:00 PM",
            location: "CADS"
        }
    ],

    22: [
        {
            title: "Job Fair",
            category: "Career",
            time: "8:00 AM",
            location: "Henry Grounds"
        }
    ]
};

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
const month = today.getMonth();
const year = today.getFullYear();

const months = [
    "January","February","March",
    "April","May","June",
    "July","August","September",
    "October","November","December"
];

monthTitle.textContent =
`${months[month]} ${year}`;

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

const firstDay = new Date(year,month,1).getDay();
const daysInMonth = new Date(year,month+1,0).getDate();

for(let i=0;i<firstDay;i++){
    const blank = document.createElement("div");
    grid.appendChild(blank);
}

for(let day = 1; day <= daysInMonth; day++){

    const cell = document.createElement("div");
    cell.classList.add("day-cell");

    if(day === today.getDate()){
        cell.classList.add("today");
    }

    cell.innerHTML = `<div class="day-number">${day}</div>`;

    if(events[day]){

        events[day].forEach(event => {

            const dot = document.createElement("div");
            dot.classList.add("event-dot");

            if(event.category === "Academic")
                dot.classList.add("green");

            if(event.category === "Non-Academic")
                dot.classList.add("yellow");

            if(event.category === "Career")
                dot.classList.add("blue");

            cell.appendChild(dot);

        });

    }

    cell.addEventListener("click", () => {
        openModal(day);
    });

    grid.appendChild(cell);
}
const modal = document.getElementById("eventModal");
const modalDate = document.getElementById("modalDate");
const modalEvents = document.getElementById("modalEvents");
const closeBtn = document.querySelector(".close-btn");

function openModal(day){

    modal.classList.add("show");

    modalDate.textContent = `${months[month]} ${day}, ${year}`;

    modalEvents.innerHTML = "";

    if(!events[day]){
        modalEvents.innerHTML = "<p>No events scheduled.</p>";
        return;
    }

    events[day].forEach(event => {

        modalEvents.innerHTML += `
            <div class="event-card">
                <h4>${event.title}</h4>
                <p><strong>Category:</strong> ${event.category}</p>
                <p><strong>Time:</strong> ${event.time}</p>
                <p><strong>Location:</strong> ${event.location}</p>
            </div>
        `;

    });

}

closeBtn.onclick = () => {
    modal.classList.remove("show");
};

window.onclick = (e) => {
    if(e.target === modal){
        modal.classList.remove("show");
    }
};
