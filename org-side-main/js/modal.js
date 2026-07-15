/**
 * Shared modal helper for org-side-main.
 * Replaces native alert()/confirm() with a styled modal, matching the
 * .modal-overlay / .modal-box visual language already used elsewhere
 * (e.g. the remarks modal on the officer dashboard), under the
 * "app-modal-*" class names defined in css/modal.css so it can't clash
 * with any existing modal markup on the page.
 *
 * Usage:
 *   showModal("Event updated successfully!");
 *   showModal("Something went wrong.", { type: "error", title: "Error" });
 *   const ok = await showConfirmModal("Are you sure you want to delete this event?");
 */

function ensureAppModalRoot() {
    let overlay = document.getElementById("appModalOverlay");
    if (overlay) return overlay;

    overlay = document.createElement("div");
    overlay.id = "appModalOverlay";
    overlay.className = "app-modal-overlay";
    overlay.innerHTML = `
        <div class="app-modal-box">
            <div class="app-modal-icon" id="appModalIcon"></div>
            <div class="app-modal-title" id="appModalTitle"></div>
            <div class="app-modal-message" id="appModalMessage"></div>
            <div class="app-modal-actions" id="appModalActions"></div>
        </div>
    `;
    document.body.appendChild(overlay);

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) {
            overlay.classList.remove("show");
        }
    });

    return overlay;
}

const ICONS = {
    success: "&#10003;",
    error: "&times;",
    confirm: "?",
};

/**
 * Shows a simple message modal with a single acknowledgement button.
 * Returns a Promise that resolves when the user dismisses it.
 */
function showModal(message, options = {}) {
    const {
        title = options.type === "error" ? "Error" : "Success",
        type = "success",
        buttonText = "OK",
    } = options;

    const overlay = ensureAppModalRoot();
    const icon = document.getElementById("appModalIcon");
    const titleEl = document.getElementById("appModalTitle");
    const messageEl = document.getElementById("appModalMessage");
    const actions = document.getElementById("appModalActions");

    icon.className = `app-modal-icon ${type}`;
    icon.innerHTML = ICONS[type] || ICONS.success;
    titleEl.textContent = title;
    messageEl.textContent = message;
    actions.innerHTML = "";

    return new Promise((resolve) => {
        const okBtn = document.createElement("button");
        okBtn.type = "button";
        okBtn.className = "app-modal-btn primary";
        okBtn.textContent = buttonText;
        okBtn.addEventListener("click", () => {
            overlay.classList.remove("show");
            resolve();
        });
        actions.appendChild(okBtn);

        requestAnimationFrame(() => overlay.classList.add("show"));
    });
}

/**
 * Shows a Yes/Cancel confirmation modal in place of window.confirm().
 * Returns a Promise<boolean> resolved true if confirmed, false if cancelled.
 */
function showConfirmModal(message, options = {}) {
    const {
        title = "Please confirm",
        confirmText = "Yes",
        cancelText = "Cancel",
        danger = false,
    } = options;

    const overlay = ensureAppModalRoot();
    const icon = document.getElementById("appModalIcon");
    const titleEl = document.getElementById("appModalTitle");
    const messageEl = document.getElementById("appModalMessage");
    const actions = document.getElementById("appModalActions");

    icon.className = "app-modal-icon confirm";
    icon.innerHTML = ICONS.confirm;
    titleEl.textContent = title;
    messageEl.textContent = message;
    actions.innerHTML = "";

    return new Promise((resolve) => {
        const cancelBtn = document.createElement("button");
        cancelBtn.type = "button";
        cancelBtn.className = "app-modal-btn secondary";
        cancelBtn.textContent = cancelText;
        cancelBtn.addEventListener("click", () => {
            overlay.classList.remove("show");
            resolve(false);
        });

        const confirmBtn = document.createElement("button");
        confirmBtn.type = "button";
        confirmBtn.className = `app-modal-btn ${danger ? "danger" : "primary"}`;
        confirmBtn.textContent = confirmText;
        confirmBtn.addEventListener("click", () => {
            overlay.classList.remove("show");
            resolve(true);
        });

        actions.appendChild(cancelBtn);
        actions.appendChild(confirmBtn);

        requestAnimationFrame(() => overlay.classList.add("show"));
    });
}
