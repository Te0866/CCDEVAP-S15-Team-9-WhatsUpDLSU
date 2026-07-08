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



const params = new URLSearchParams(window.location.search);
const userId = params.get("id");

if(userId){
    document.getElementById("formTitle").textContent = "Edit User";
    document.getElementById("createBtn").textContent = "Save Changes";
}

document.getElementById("createBtn").addEventListener("click",()=>{
    const username = document.getElementById("usernameInput").value.trim();
    const password = document.getElementById("passwordInput").value.trim();

    if(!username || !password){
        alert("Please fill in both username and password.");
        return;
    }

    alert(userId ? "User Updated Successfully" : "User Created Successfully");
    window.location.href = "account-management.html";
});
