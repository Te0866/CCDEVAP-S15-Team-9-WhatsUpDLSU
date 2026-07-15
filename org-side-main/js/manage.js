const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", () => {
    dropdownMenu.classList.remove("show");
});


document.querySelectorAll(".status-badge").forEach(badge => {
    const status = badge.textContent.trim().toLowerCase();

    badge.classList.remove("pending", "approved", "rejected");
    badge.classList.add(status);
});

const pending = document.querySelectorAll(".pending-card").length;
const approved = document.querySelectorAll(".approved-card").length;
const rejected = document.querySelectorAll(".rejected-card").length;

document.getElementById("pendingCount").textContent = pending;
document.getElementById("approvedCount").textContent = approved;
document.getElementById("rejectedCount").textContent = rejected;


const searchInput = document.getElementById("searchInput");
const filterDate = document.getElementById("filterDate");
const filterCategory = document.getElementById("filterCategory");
const filterStatus = document.getElementById("filterStatus");
const clearFiltersBtn = document.getElementById("clearFiltersBtn");
const noResultsMsg = document.getElementById("noResultsMsg");
const eventCards = document.querySelectorAll(".event-card");

function applyFilters() {
    const searchText = searchInput.value.toLowerCase().trim();
    const dateValue = filterDate.value;
    const categoryValue = filterCategory.value;
    const statusValue = filterStatus.value;

    let visibleCount = 0;

    eventCards.forEach(card => {
        const matchesSearch =
            searchText === "" || card.dataset.title.includes(searchText);

        const matchesDate =
            dateValue === "" || card.dataset.date === dateValue;

        const matchesCategory =
            categoryValue === "" || card.dataset.category === categoryValue;

        const matchesStatus =
            statusValue === "" || card.dataset.status === statusValue;

        const isMatch =
            matchesSearch && matchesDate && matchesCategory && matchesStatus;

        card.style.display = isMatch ? "" : "none";

        if (isMatch) visibleCount++;
    });

    if (noResultsMsg) {
        noResultsMsg.style.display =
            visibleCount === 0 && eventCards.length > 0 ? "" : "none";
    }
}

if (searchInput) {
    searchInput.addEventListener("input", applyFilters);
    filterDate.addEventListener("change", applyFilters);
    filterCategory.addEventListener("change", applyFilters);
    filterStatus.addEventListener("change", applyFilters);

    clearFiltersBtn.addEventListener("click", () => {
        searchInput.value = "";
        filterDate.value = "";
        filterCategory.value = "";
        filterStatus.value = "";
        applyFilters();
    });
}
