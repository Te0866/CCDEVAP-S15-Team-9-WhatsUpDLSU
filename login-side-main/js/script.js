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

document.getElementById("loginForm").addEventListener("submit", (event) => {
    event.preventDefault();
    alert("Login submitted.");
});