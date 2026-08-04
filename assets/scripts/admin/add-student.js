(function () {
    try {
        if (!document.body.classList.contains("s-admin-add-student")) return;
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
            window.location.href = 'account-management.php';
        }
       window.closeSuccessModal = closeSuccessModal;

        window.addEventListener('load', () => {
            const successMsg = document.getElementById('php-success-msg')?.dataset.message;
            if (successMsg) {
                showSuccessModal(successMsg);
            }
        });

        // Retype password validation
        const passwordInput = document.getElementById('passwordInput');
        const retypePasswordInput = document.getElementById('retypePasswordInput');
        const passwordMatchMessage = document.getElementById('passwordMatchMessage');
        const studentForm = document.getElementById('studentForm');

        function checkPasswordsMatch() {
            if (!passwordInput || !retypePasswordInput || !passwordMatchMessage) return true;

            if (retypePasswordInput.value === '') {
                passwordMatchMessage.textContent = '';
                passwordMatchMessage.classList.remove('error', 'success');
                return false;
            }

            if (passwordInput.value === retypePasswordInput.value) {
                passwordMatchMessage.textContent = 'Passwords match';
                passwordMatchMessage.classList.remove('error');
                passwordMatchMessage.classList.add('success');
                return true;
            }

            passwordMatchMessage.textContent = 'Passwords do not match';
            passwordMatchMessage.classList.remove('success');
            passwordMatchMessage.classList.add('error');
            return false;
        }

        if (passwordInput && retypePasswordInput) {
            passwordInput.addEventListener('input', checkPasswordsMatch);
            retypePasswordInput.addEventListener('input', checkPasswordsMatch);
        }

        if (studentForm) {
            studentForm.addEventListener('submit', (e) => {
                if (passwordInput.value !== retypePasswordInput.value) {
                    e.preventDefault();
                    passwordMatchMessage.textContent = 'Passwords do not match';
                    passwordMatchMessage.classList.remove('success');
                    passwordMatchMessage.classList.add('error');
                    retypePasswordInput.focus();
                }
            });
        }
    } catch (err) {
        console.error("[script.js] error in admin/js/add-student.js:", err);
    }
})();
