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

document.getElementById("updateBtn").addEventListener("click", () => {
    alert("Profile updated successfully.");
});
