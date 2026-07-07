const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click",(e)=>{
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.querySelectorAll(".event-card")
.forEach(card => {
    card.addEventListener("click", () => {
        window.location.href = "edit-event.html";
    });
});