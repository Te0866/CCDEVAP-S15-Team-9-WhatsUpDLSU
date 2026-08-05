(function () {
    try {
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

        // Delete confirmation modal
        const deleteModal = document.getElementById("deleteModal");
        const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
        const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
        let selectedDeleteForm = null;

        if (deleteModal && confirmDeleteBtn && cancelDeleteBtn) {
            document.querySelectorAll(".delete-form .delete-btn").forEach(button => {
                button.addEventListener("click", function () {
                    selectedDeleteForm = this.closest("form");
                    deleteModal.classList.add("show");
                });
            });

            confirmDeleteBtn.addEventListener("click", () => {
                if (selectedDeleteForm) {
                    selectedDeleteForm.submit();
                }
            });

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
        }

        // Searching
        function filterAccountsTable() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const typeFilter = document.getElementById('typeFilter')?.value || 'all';

            const rows = document.querySelectorAll('#usersTableBody tr');

            rows.forEach(row => {
                if (row.cells.length < 2) return;

                const name = row.cells[0]?.textContent.toLowerCase() || '';
                const type = row.getAttribute('data-type') || '';

                let match = true;
                if (searchTerm && !name.includes(searchTerm)) match = false;
                if (typeFilter !== 'all' && type !== typeFilter) match = false;

                row.style.display = match ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');

            if (searchInput) searchInput.addEventListener('input', filterAccountsTable);
            if (typeFilter) typeFilter.addEventListener('change', filterAccountsTable);

            filterAccountsTable();
        });
    } catch (err) {
        console.error("[script.js] error in admin/js/account-management.js:", err);
    }
})();
