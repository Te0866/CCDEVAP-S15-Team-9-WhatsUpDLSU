(function () {
    try {
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click", (event) => {
            event.stopPropagation();
            dropdownMenu.classList.toggle("show");

        });

        document.addEventListener("click", (event) => {
            if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)){
                dropdownMenu.classList.remove("show");
            }
        });



        const container = document.getElementById("interestedEventsContainer");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        const TAG_CLASS = {
            "ACADEMIC": "tag-academic",
            "NON-ACADEMIC": "tag-nonacademic",
            "CAREER": "tag-career"
        };

        function renderCarousel(events) {

            container.innerHTML = "";

            if (!events || events.length === 0) {
                container.innerHTML = `
                    <div class="event-card event-card-empty">
                        <h3>No Events Yet</h3>
                        <p>Add events from the Events page</p>
                    </div>
                `;
                return;
            }

            events.forEach(event => {
                const tagClass = TAG_CLASS[(event.category || "").toUpperCase()] || "tag-academic";

                container.innerHTML += `
                    <div class="event-card" onclick="location.href='events.php?id=${event.id}'">
                        <h3>${event.title}</h3>
                        <p>${event.date}</p>
                        <span class="event-tag ${tagClass}">${event.category}</span>
                    </div>
                `;
            });
        }

        function scrollEvents(direction) {
            const scrollAmount = 220;
            container.scrollBy({
                left: scrollAmount * direction,
                behavior: "smooth"
            });
        }

        fetch("api/get-interested-events.php")
            .then(res => res.json())
            .then(data => {
                renderCarousel(data);
            })
            .catch(err => console.error(err));

        nextBtn.addEventListener("click", () => scrollEvents(1));
        prevBtn.addEventListener("click", () => scrollEvents(-1));

        const countElements = {
            "ACADEMIC": document.getElementById("countAcademic"),
            "NON-ACADEMIC": document.getElementById("countNonAcademic"),
            "CAREER": document.getElementById("countCareer")
        };

        const chartCanvas = document.getElementById("studentChart");

        fetch("api/get-category-stats.php")
        .then(res => res.json())
        .then(data => {

            const labels = data.map(item => item.CATEGORY);
            const values = data.map(item => item.total);

            data.forEach(item => {
                const el = countElements[(item.CATEGORY || "").toUpperCase()];
                if (el) el.textContent = item.total;
            });

            new Chart(chartCanvas, {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: labels.map(label => CATEGORY_COLORS[label] || "#8a9791")
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "bottom"
                        }
                    }
                }
            });

        });

        const popularCanvas = document.getElementById("popularChart");

        fetch("api/get-popular-events.php")
        .then(res => res.json())
        .then(data => {

            const labels = data.map(item => item.TITLE);
            const values = data.map(item => Number(item.interested));

            new Chart(popularCanvas, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Interested Students",
                        data: values,
                        backgroundColor: [
                            "#087f5b",
                            "#1fa67a",
                            "#39b88c",
                            "#63c9a7",
                            "#8edcc2"
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: "y",
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false
                        }
                    },

                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Interested Students"
                            }
                        },
                        y: {
                            title: {
                                display: false
                            }
                        }
                    }
                }
            });

        })
        .catch(error => {
            console.error("Error loading popular events:", error);
        });
        const myInterestsCanvas = document.getElementById("myInterestsChart");

        fetch("api/get-my-category-stats.php")
        .then(res => res.json())
        .then(data => {

            if (data.length === 0) {
                myInterestsCanvas.parentElement.innerHTML =
                    "<h2 class='chart-title'>My Interests by Category</h2><p style='text-align:center;color:#777;'>No interested events yet.</p>";
                return;
            }

            const labels = data.map(item => item.CATEGORY);
            const values = data.map(item => Number(item.total));

            new Chart(myInterestsCanvas, {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: labels.map(label => CATEGORY_COLORS[label] || "#8a9791")
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "bottom"
                        }
                    }
                }
            });

        })
        .catch(error => {
            console.error("Error loading personal interest stats:", error);
        });
    } catch (err) {
        console.error("[script.js] error in student/js/dashboard.js:", err);
    }
})();
