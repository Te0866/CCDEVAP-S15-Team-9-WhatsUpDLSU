const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");


profileBtn.addEventListener("click",(e)=>{ e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click",(e)=>{
    if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
        dropdownMenu.classList.remove("show");
    }
});


document.getElementById("approveBtn").addEventListener("click",()=>{
    alert("Event Approved Successfully");
});


document.getElementById("rejectBtn").addEventListener("click",()=>{
    alert("Event Rejected");
});