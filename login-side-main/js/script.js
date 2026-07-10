
const passwordInput = document.getElementById("password");
const toggleButton = document.getElementById("togglePassword");

toggleButton.addEventListener("click",() => {
    if(passwordInput.type === "password"){
        passwordInput.type = "text";
        toggleButton.textContent = "Hide";
    }
    else{
        passwordInput.type = "password";
        toggleButton.textContent = "Show";
    }
});

// document.getElementById("loginForm").addEventListener("submit", (event) => {
//     event.preventDefault();
//     const page = window.location.pathname.split("/").pop();
// console.log(window.location.pathname);
// console.log(page);
//     let redirectPath;
//     switch (page) {
//         case "login.html":
//             redirectPath = "../student-side-main/dashboard.php";
//             break;
//         case "admin-login.html":
//             redirectPath = "../admin-side-main/admin-dashboard.html";
//             break;
//         case "officer-login.html":
//             redirectPath = "../org-side-main/officer-dashboard.html";
//             break;
//     }

//     window.location.href = redirectPath;
// });
