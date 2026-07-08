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
        if(
            currentMonth===today.getMonth() &&
            currentYear===today.getFullYear() &&
            events[day]
        ){

            events[day].forEach(event=>{

                const dot=document.createElement("div");
                dot.classList.add("event-dot");

                if(event.category==="Academic")
                    dot.classList.add("green");

                if(event.category==="Non-Academic")
                    dot.classList.add("yellow");

                if(event.category==="Career")
                    dot.classList.add("blue");

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

    if(
    currentMonth!==today.getMonth() || currentYear!==today.getFullYear() ||!events[day]){
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
document.getElementById("prevMonth").onclick = () => {

    currentMonth--;

    if(currentMonth < 0){
        currentMonth = 11;
        currentYear--;
    }

    renderCalendar();

};

document.getElementById("nextMonth").onclick = () => {

    currentMonth++;

    if(currentMonth > 11){
        currentMonth = 0;
        currentYear++;
    }

    renderCalendar();

};
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
renderCalendar();
