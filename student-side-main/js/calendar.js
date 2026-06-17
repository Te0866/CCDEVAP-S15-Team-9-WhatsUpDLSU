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

for(let day=1; day<=daysInMonth; day++){

    const cell = document.createElement("div");
    cell.classList.add("day-cell");

    if(day===today.getDate()){
        cell.classList.add("today");
    }

    cell.innerHTML = `<div class="day-number">${day}</div>`;

    if(day===5 || day===14){
        cell.innerHTML += '<div class="event-dot green"></div>';
    }

    if(day===10){
        cell.innerHTML += '<div class="event-dot yellow"></div>';
    }

    if(day===22){
        cell.innerHTML += '<div class="event-dot blue"></div>';
    }

    grid.appendChild(cell);
}