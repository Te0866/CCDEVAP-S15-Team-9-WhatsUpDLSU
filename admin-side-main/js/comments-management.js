const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

if (profileBtn && dropdownMenu) {
    profileBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove("show");
        }
    });
}

// Modal
const successModal = document.getElementById("successModal");
const deleteModal = document.getElementById("deleteModal");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
let selectedDeleteForm = null;

document.querySelectorAll(".delete-form .delete-btn").forEach(button => {
    button.addEventListener("click", function () {
        selectedDeleteForm = this.closest("form");
        deleteModal.classList.add("show");
    });
});

// Delete
confirmDeleteBtn.addEventListener("click", () => {
    if (selectedDeleteForm) {
        selectedDeleteForm.submit();
    }
});

// Cancel 
cancelDeleteBtn.addEventListener("click", () => {
    deleteModal.classList.remove("show");
    selectedDeleteForm = null;
});

deleteModal.addEventListener("click", (e) => {
    if (e.target === deleteModal) {
        deleteModal.classList.remove("show");
        selectedDeleteForm = null;
    }
});

// Searching
function filterCommentsTable() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const eventFilter = document.getElementById('eventFilter')?.value || 'all';

    const rows = document.querySelectorAll('#commentsTableBody tr');

    rows.forEach(row => {
        if (row.cells.length < 2) return;

        const username = row.cells[0]?.textContent.toLowerCase() || '';
        const text = row.cells[1]?.textContent.toLowerCase() || '';
        const eventId = row.getAttribute('data-event-id') || '';

        let match = true;
        if (searchTerm && !username.includes(searchTerm) && !text.includes(searchTerm)) match = false;
        if (eventFilter !== 'all' && eventId !== eventFilter) match = false;

        row.style.display = match ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const eventFilter = document.getElementById('eventFilter');

    if (searchInput) searchInput.addEventListener('input', filterCommentsTable);
    if (eventFilter) eventFilter.addEventListener('change', filterCommentsTable);

    filterCommentsTable();

    const phpMsg = document.getElementById("php-success-msg");
    if (phpMsg) {
        showSuccessModal(phpMsg.dataset.message);
    }
});
