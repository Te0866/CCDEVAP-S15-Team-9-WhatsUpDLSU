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

/* Remove file */

const removeFile = document.getElementById("removeFile");
removeFile.addEventListener("click",(e)=>{
    e.stopPropagation();
    document.querySelector(".file-chip").remove();
});

/* Submit */

document.getElementById("submitBtn").addEventListener("click",()=>{
    alert("Event updated successfully!");
});

/* Delete */

document.getElementById("deleteBtn").addEventListener("click",()=>{
    const confirmed =confirm("Delete this event?");
    if(confirmed){
        alert("Event deleted.");
        window.location.href ="manage.html";
    }
});