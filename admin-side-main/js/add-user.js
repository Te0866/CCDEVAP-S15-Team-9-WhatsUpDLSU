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

window.addEventListener('load', () => {
    const successMsg = document.getElementById('php-success-msg')?.dataset.message;
    if (successMsg) {
        showSuccessModal(successMsg);
    }
});
