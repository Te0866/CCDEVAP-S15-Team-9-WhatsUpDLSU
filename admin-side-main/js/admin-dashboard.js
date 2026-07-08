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


/* View button */
document.querySelectorAll(".view-btn").forEach(btn=>{
    btn.addEventListener("click",(e)=>{
        e.stopPropagation();
        window.location.href = "admin-event-review.html";
    });
});


/* Approve and Reject button */
document.querySelectorAll(".approve-btn").forEach(btn=>{
    btn.addEventListener("click",(e)=>{
        e.stopPropagation();
        const row = btn.closest(".event-row");
        setRowStatus(row,"approved","Approved");
    });
});

document.querySelectorAll(".reject-btn").forEach(btn=>{
    btn.addEventListener("click",(e)=>{
        e.stopPropagation();
        const row = btn.closest(".event-row");
        setRowStatus(row,"rejected","Rejected");
    });
});

function setRowStatus(row, statusClass, statusLabel){
    if(!row) return;
    row.dataset.status = statusClass;

    const badge = row.querySelector(".status-badge");
    if(badge){
        badge.classList.remove("pending","approved","rejected");
        badge.classList.add(statusClass);
        badge.textContent = statusLabel;
    }
}


/* Searching */
const searchInput = document.getElementById("searchInput");
if(searchInput){
    searchInput.addEventListener("input", ()=>{
        const query = searchInput.value.toLowerCase();
        document.querySelectorAll(".event-row").forEach(row=>{
            const eventName = row.children[0].textContent.toLowerCase();
            row.style.display = eventName.includes(query) ? "" : "none";
        });
    });
}
