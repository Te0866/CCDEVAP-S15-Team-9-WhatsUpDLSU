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

function setupPasswordToggle(inputId, buttonId) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);

    button.addEventListener("click", () => {
        if (input.type === "password") {
            input.type = "text";
            button.textContent = "Hide";
        } else {
            input.type = "password";
            button.textContent = "Show";
        }
    });
}

setupPasswordToggle("password", "togglePassword");
setupPasswordToggle("confirmPassword", "toggleConfirmPassword");

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
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (!username) {
        showAlert("Error", "Username cannot be empty.");
        return;
    }
    if (password !== confirmPassword) {
        showAlert("Error", "Passwords do not match.");
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
        });

        const result = await response.json();

        if (result.success) {
            showAlert("Success", "Profile updated successfully.");
            setTimeout(() => {
                window.location.href = "dashboard.php";
            }, 1200);
        } else {
            showAlert("Update Failed", result.error || "Unknown error");
        }
    } catch (err) {
        console.error("Update error:", err);
        showAlert("Error", "Something went wrong while updating your profile.");
    }
});
