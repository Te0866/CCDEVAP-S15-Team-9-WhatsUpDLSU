(function () {
    try {
        if (!document.body.classList.contains("s-admin-add-event")) return;
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

        const successModal = document.createElement('div');
        successModal.className = 'modal';
        successModal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Success</h2>
                </div>
                <div class="modal-body" id="modalMessage"></div>
                <div class="modal-footer">
                    <button class="modal-btn" onclick="closeSuccessModal()">OK</button>
                </div>
            </div>
        `;
        document.body.appendChild(successModal);

        function showSuccessModal(message) {
            document.getElementById('modalMessage').textContent = message;
            successModal.classList.add('show');
        }

        function closeSuccessModal() {
            successModal.classList.remove('show');
            window.location.href = 'admin-dashboard.php';
        }
       window.closeSuccessModal = closeSuccessModal;

        window.addEventListener('load', () => {
            const successMsg = document.getElementById('php-success-msg')?.dataset.message;
            if (successMsg) {
                showSuccessModal(successMsg);
            }
        });

        const uploadBox = document.querySelector(".upload-box");
        const fileInput = document.getElementById("eventImages");
        const uploadIcon = document.querySelector(".upload-icon");
        const existingImagesInput = document.getElementById("existingImagesInput");
        const MAX_IMAGES = 4;
        let selectedFiles = [];
        let existingImages = [];

        if (existingImagesInput && existingImagesInput.value.trim() !== "") {
            existingImages = existingImagesInput.value.split(",")
                .map(s => s.trim())
                .filter(s => s !== "");
        }

        if (uploadBox && fileInput) {
            uploadBox.addEventListener("click", (e) => {
                // Don't open file picker when clicking remove on a chip
                if (e.target.closest(".file-chip button")) return;
                fileInput.click();
            });

            fileInput.addEventListener("change", () => {
                const incoming = Array.from(fileInput.files || []);
                const remaining = MAX_IMAGES - existingImages.length - selectedFiles.length;
                if (remaining <= 0) {
                    fileInput.value = "";
                    return;
                }
                const toAdd = incoming.slice(0, remaining);
                selectedFiles = selectedFiles.concat(toAdd);
                renderChips();
                syncInputFiles();
            });
        }

        function syncExistingInput() {
            if (existingImagesInput) {
                existingImagesInput.value = existingImages.join(",");
            }
        }

        function renderChips() {
            if (!uploadBox) return;
            uploadBox.querySelectorAll(".file-chip, .upload-chips").forEach(el => el.remove());

            const total = existingImages.length + selectedFiles.length;
            if (total === 0) return;

            const chipsWrap = document.createElement("div");
            chipsWrap.className = "upload-chips";

            existingImages.forEach((name, index) => {
                const chip = document.createElement("div");
                chip.className = "file-chip";

                const nameSpan = document.createElement("span");
                nameSpan.textContent = name;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "×";
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    existingImages.splice(index, 1);
                    syncExistingInput();
                    renderChips();
                });

                chip.appendChild(nameSpan);
                chip.appendChild(removeBtn);
                chipsWrap.appendChild(chip);
            });

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
            selectedFiles.forEach(f => dt.items.add(f));
            fileInput.files = dt.files;
        }

        // Show existing image chips on load (edit mode)
        renderChips();
    } catch (err) {
        console.error("[script.js] error in admin/js/add-event.js:", err);
    }
})();
