const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (event) => {
    event.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", (event) => {
    if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.remove("show");
    }
});

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", () => {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePassword.textContent = "Hide";
    } else {
        passwordInput.type = "password";
        togglePassword.textContent = "Show";
    }
});

const profileImage = document.getElementById("profileImage");
const profilePreview = document.getElementById("profilePreview");

profileImage.addEventListener("change", () => {
    const file = profileImage.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        profilePreview.src = e.target.result;
    };

    reader.readAsDataURL(file);
});

document.getElementById("updateBtn").addEventListener("click", async () => {
    const username = document.getElementById("username").value.trim();
    const password = passwordInput.value;

    if (!username) {
        alert("Username and password cannot be empty.");
        return;
    }

    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    const file = profileImage.files[0];
    if (file) {
        formData.append("profileImage", file);
    }

    try {
        const response = await fetch("update-profile.php", {
            method: "POST",
            body: formData
            // NOTE: no Content-Type header — the browser sets it automatically for FormData, including the required boundary string
        });

        const result = await response.json();

        if (result.success) {
            alert("Profile updated successfully.");
        } else {
            alert("Update failed: " + (result.error || "Unknown error"));
        }
    } catch (err) {
        console.error("Update error:", err);
        alert("Something went wrong while updating your profile.");
    }
});
