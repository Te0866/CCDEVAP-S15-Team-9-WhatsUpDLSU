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

const uploadBox = document.querySelector(".upload-box");
const fileInput = document.getElementById("eventImage");
const uploadIcon = document.querySelector(".upload-icon");

uploadBox.addEventListener("click",()=>{
    fileInput.click();
});

fileInput.addEventListener("change", () => {
    const oldChip = uploadBox.querySelector(".file-chip");
    if (oldChip) oldChip.remove();

    if (fileInput.files.length > 0) {
        const chip = document.createElement("div");
        chip.className = "file-chip";

        const nameSpan = document.createElement("span");
        nameSpan.textContent = fileInput.files[0].name;

        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.textContent = "×";
        removeBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            fileInput.value = "";
            chip.remove();
        });

        chip.appendChild(nameSpan);
        chip.appendChild(removeBtn);
        uploadBox.insertBefore(chip, uploadIcon);
    }
});

const form = document.querySelector(".form-card");

document.getElementById("clearBtn").addEventListener("click",()=>{
    form.querySelectorAll("input, textarea, select").forEach(field=>{
        if(field.type !== "file"){
            field.value = "";
        }
    });
});
