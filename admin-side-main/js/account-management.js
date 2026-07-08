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


/* Delete button */
document.querySelectorAll(".delete-btn").forEach(btn=>{
    btn.addEventListener("click",()=>{
        const row = btn.closest(".user-row");
        if(row && confirm("Are you sure you want to delete this user?")){
            row.remove();
        }
    });
});


/* Search and filter */
const searchInput = document.getElementById("searchInput");
const typeFilter = document.getElementById("typeFilter");

function applyFilters(){
    const query = searchInput ? searchInput.value.toLowerCase() : "";
    const type = typeFilter ? typeFilter.value : "all";

    document.querySelectorAll(".user-row").forEach(row=>{
        const name = row.children[0].textContent.toLowerCase();
        const matchesSearch = name.includes(query);
        const matchesType = (type === "all") || (row.dataset.type === type);
        row.style.display = (matchesSearch && matchesType) ? "" : "none";
    });
}

if(searchInput){
    searchInput.addEventListener("input", applyFilters);
}

if(typeFilter){
    typeFilter.addEventListener("change", applyFilters);
}
