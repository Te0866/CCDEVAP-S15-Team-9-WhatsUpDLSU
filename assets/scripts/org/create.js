(function () {
    try {
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
        const fileInput = document.getElementById("eventImages") || document.getElementById("eventImage");
        const uploadIcon = document.querySelector(".upload-icon");
        const MAX_IMAGES = 4;
        let selectedFiles = [];

        if (uploadBox && fileInput) {
            uploadBox.addEventListener("click", (e) => {
                if (e.target.closest(".file-chip button")) return;
                fileInput.click();
            });

            fileInput.addEventListener("change", () => {
                const incoming = Array.from(fileInput.files || []);
                const remaining = MAX_IMAGES - selectedFiles.length;
                if (remaining <= 0) {
                    fileInput.value = "";
                    return;
                }
                selectedFiles = selectedFiles.concat(incoming.slice(0, remaining));
                renderChips();
                syncInputFiles();
            });
        }

        function renderChips() {
            if (!uploadBox) return;
            uploadBox.querySelectorAll(".file-chip, .upload-chips").forEach((el) => el.remove());

            if (selectedFiles.length === 0) return;

            const chipsWrap = document.createElement("div");
            chipsWrap.className = "upload-chips";

            selectedFiles.forEach((file, index) => {
                const chip = document.createElement("div");
                chip.className = "file-chip";

                const nameSpan = document.createElement("span");
                nameSpan.textContent = file.name;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "×";
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    selectedFiles.splice(index, 1);
                    renderChips();
                    syncInputFiles();
                });

                chip.appendChild(nameSpan);
                chip.appendChild(removeBtn);
                chipsWrap.appendChild(chip);
            });

            if (uploadIcon) {
                uploadBox.insertBefore(chipsWrap, uploadIcon);
            } else {
                uploadBox.appendChild(chipsWrap);
            }
        }

        function syncInputFiles() {
            if (!fileInput) return;
            const dt = new DataTransfer();
            selectedFiles.forEach((f) => dt.items.add(f));
            fileInput.files = dt.files;
        }

        const form = document.querySelector(".form-card");

        const clearBtn = document.getElementById("clearBtn");
        if (clearBtn && form) {
            clearBtn.addEventListener("click", () => {
                form.querySelectorAll("input, textarea, select").forEach((field) => {
                    if (field.type !== "file" && field.type !== "hidden") {
                        field.value = "";
                    }
                });
                selectedFiles = [];
                if (fileInput) fileInput.value = "";
                renderChips();
            });
        }
    } catch (err) {
        console.error("[script.js] error in org/js/create.js:", err);
    }
})();
