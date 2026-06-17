
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

/* Upload */

const uploadBox = document.querySelector(".upload-box");
const fileInput = document.getElementById("eventImage");

uploadBox.addEventListener("click",()=>{
    fileInput.click();
});

const form = document.querySelector(".form-container");

document.getElementById("clearBtn").addEventListener("click",()=>{
    form.querySelectorAll("input, textarea, select").forEach(field=>{
        if(field.type !== "file"){
            field.value = "";
        }
    });
});

document.getElementById("submitBtn").addEventListener("click",()=>{
    alert("Event submitted successfully!");
});