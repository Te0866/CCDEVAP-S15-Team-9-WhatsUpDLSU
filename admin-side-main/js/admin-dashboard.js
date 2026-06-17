const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");


profileBtn.addEventListener("click",(e)=>{
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click",(e)=>{
    if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
        dropdownMenu.classList.remove("show");
    }
});


document.querySelectorAll(".event-card").forEach(card=>{
    card.addEventListener("click",()=>{ 
        window.location.href = "admin-event-review.html";
    });
});

document.querySelectorAll(".event-card").forEach(card=>{
    card.addEventListener("click",()=>{
        window.location.href ="admin-event-review.html";
    });
});