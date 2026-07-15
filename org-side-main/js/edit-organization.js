const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", (e) => {
    if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
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
const uploadPicBtn = document.getElementById("uploadPicBtn");

uploadPicBtn.addEventListener("click", () => {
    profileImage.click();
});

profileImage.addEventListener("change", () => {
    const file = profileImage.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        profilePreview.src = e.target.result;
    };

    reader.readAsDataURL(file);
});

const formError = document.getElementById("formError");

function showError(message) {
    formError.textContent = message;
    formError.style.display = "block";
}

function clearError() {
    formError.textContent = "";
    formError.style.display = "none";
}

document.getElementById("updateBtn").addEventListener("click", async () => {
    clearError();

    const orgName = document.getElementById("orgName").value.trim();
    const password = passwordInput.value;

    if (!orgName || !password) {
        showError("Organization name and password cannot be empty.");
        return;
    }

    const formData = new FormData();
    formData.append("orgName", orgName);
    formData.append("password", password);

    const file = profileImage.files[0];
    if (file) {
        formData.append("profileImage", file);
    }

    const updateBtn = document.getElementById("updateBtn");
    updateBtn.disabled = true;
    updateBtn.textContent = "Updating...";

    try {
        const response = await fetch("update-organization-process.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            await showModal("Organization details updated successfully!");
            location.reload();
        } else {
            showError(result.error || "Update failed. Please try again.");
        }
    } catch (err) {
        console.error("Update error:", err);
        showError("Something went wrong while updating your organization details.");
    } finally {
        updateBtn.disabled = false;
        updateBtn.textContent = "Update Details";
    }
});
