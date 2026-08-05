(function () {
    try {

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
    } catch (err) {
        console.error("[script.js] error in login/js/script.js:", err);
    }
})();

/* ---- login-side-main/js/modal.js (login result modal, mirrors org-side modal.js) ---- */
(function () {
    try {
        const form = document.getElementById("loginForm");
        const modalOverlay = document.getElementById("loginModal");
        const modalIcon = document.getElementById("modalIcon");
        const modalTitle = document.getElementById("modalTitle");
        const modalMessage = document.getElementById("modalMessage");
        const modalOkBtn = document.getElementById("modalOkBtn");

        const ICONS = {
            success: "&#10003;",
            error: "&times;",
        };

        function showLoginModal(message, type) {
            const title = type === "error" ? "Error" : "Success";

            modalIcon.className = `app-modal-icon ${type}`;
            modalIcon.innerHTML = ICONS[type] || ICONS.success;
            modalTitle.textContent = title;
            modalMessage.textContent = message;

            return new Promise((resolve) => {
                modalOkBtn.onclick = () => {
                    modalOverlay.classList.remove("show");
                    resolve();
                };
                requestAnimationFrame(() => modalOverlay.classList.add("show"));
            });
        }

        modalOverlay.addEventListener("click", (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove("show");
            }
        });

        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                });

                if (response.redirected) {
                    await showLoginModal("Login successful!", "success");
                    window.location.href = response.url;
                    return;
                }

                const message = (await response.text()).trim() || "Login failed. Please try again.";
                await showLoginModal(message, "error");
            } catch (err) {
                await showLoginModal("Something went wrong. Please try again.", "error");
            }
        });
    } catch (err) {
        console.error("[login.js] error in login result modal:", err);
    }
})();
