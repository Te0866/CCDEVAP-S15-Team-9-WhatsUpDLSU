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
        function filterTable() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const dateFilter = document.getElementById('filterDate')?.value || ''; // YYYY-MM-DD
            const categoryFilter = document.getElementById('filterCategory')?.value || '';
            const statusFilter = document.getElementById('filterStatus')?.value || '';

            const rows = document.querySelectorAll('#eventsTableBody tr');

            rows.forEach(row => {
                if (row.cells.length < 2) return; 

                const title = row.cells[0]?.textContent.toLowerCase() || '';
                const category = row.getAttribute('data-category') || '';
                const status = row.getAttribute('data-status') || '';

                let match = true;

                if (searchTerm && !title.includes(searchTerm)) match = false;
                if (categoryFilter && category !== categoryFilter.toLowerCase().replace(/-/g, '')) match = false;
                if (statusFilter && status !== statusFilter.toLowerCase()) match = false;

                if (dateFilter) {
                    const hasDate = row.getAttribute('data-date') === dateFilter;
                    if (!hasDate) match = false;
                }

                row.style.display = match ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const filterDate = document.getElementById('filterDate');
            const filterCategory = document.getElementById('filterCategory');
            const filterStatus = document.getElementById('filterStatus');

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (filterDate) filterDate.addEventListener('change', filterTable);
            if (filterCategory) filterCategory.addEventListener('change', filterTable);
            if (filterStatus) filterStatus.addEventListener('change', filterTable);

            filterTable();
        });
    } catch (err) {
        console.error("[script.js] error in admin/js/admin-dashboard.js:", err);
    }
})();
