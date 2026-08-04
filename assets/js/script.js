/* ============================================================
   WhatsUpDLSU - Centralized script
   Auto-migrated from per-page/per-side js files.

   Every page's original script is wrapped in its own
   (function(){ ... })() closure so that variables/functions
   with the same name in different files (there were MANY -
   dropdownMenu, profileBtn, successModal, etc.) no longer
   collide/redeclare, which would otherwise throw a fatal
   SyntaxError and break every page's JS on the site.

   Each closure also has its own try/catch so that a runtime
   error on one page's module (e.g. an element that only
   exists on that page) can't stop every other page's module
   from running when this one file is loaded everywhere.
   ============================================================ */

/* ---- org-side-main/js/colors.js  (shared globals used by officer-dashboard.js) ---- */
// Single source of truth for colors used across charts, badges, and tags.
// If a color needs to change, change it here so every page stays in sync.

const CATEGORY_COLORS = {
    "ACADEMIC": "#3498db",
    "NON-ACADEMIC": "#9b59b6",
    "CAREER": "#f1c40f"
};

const STATUS_COLORS = {
    "PENDING": "#f0a63a",
    "APPROVED": "#28a745",
    "REJECTED": "#dc3545"
};


/* ---- org-side-main/js/modal.js  (shared globals used by create/manage/edit-event/edit-organization/officer-dashboard js) ---- */
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


/* ---- centralized dark mode toggle ----
   student-side-main/js/darkmode.js, admin-side-main/js/darkmode.js and
   org-side-main/js/darkmode.js were all functionally identical (only
   whitespace differed). Loading all three copies in one file would
   redeclare `const darkButtons` three times -> fatal SyntaxError.
   Kept as ONE copy here; still reads/writes the same "theme" key in
   localStorage and toggles the same body.dark-mode class + .dark-mode-btn
   buttons as before, so behavior is unchanged. */
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
}

const darkButtons = document.querySelectorAll(".dark-mode-btn");

darkButtons.forEach(button => {
    button.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        if (document.body.classList.contains("dark-mode")) {
            localStorage.setItem("theme", "dark");
        } else {
            localStorage.setItem("theme", "light");
        }
    });
});

/* ---- login-side-main/js/script.js ---- */
(function () {
    try {

        const passwordInput = document.getElementById("password");
        const toggleButton = document.getElementById("togglePassword");

        toggleButton.addEventListener("click",() => {
            if(passwordInput.type === "password"){
                passwordInput.type = "text";
                toggleButton.textContent = "Hide";
            }
            else{
                passwordInput.type = "password";
                toggleButton.textContent = "Show";
            }
        });

        // document.getElementById("loginForm").addEventListener("submit", (event) => {
        //     event.preventDefault();
        //     const page = window.location.pathname.split("/").pop();
        // console.log(window.location.pathname);
        // console.log(page);
        //     let redirectPath;
        //     switch (page) {
        //         case "login.html":
        //             redirectPath = "../student-side-main/dashboard.php";
        //             break;
        //         case "admin-login.html":
        //             redirectPath = "../admin-side-main/admin-dashboard.html";
        //             break;
        //         case "officer-login.html":
        //             redirectPath = "../org-side-main/officer-dashboard.html";
        //             break;
        //     }

        //     window.location.href = redirectPath;
        // });
    } catch (err) {
        console.error("[script.js] error in login/js/script.js:", err);
    }
})();

/* ---- login-side-main/js/create-account.js ---- */
(function () {
    try {
        document.addEventListener('DOMContentLoaded', function () {
            // Show/Hide password toggles
            document.querySelectorAll('.toggle-visibility').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    btn.textContent = isHidden ? 'Hide' : 'Show';
                });
            });

            // Confirm password live match check
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            const matchMessage = document.getElementById('matchMessage');

            function checkMatch() {
                if (confirmPassword.value.length === 0) {
                    matchMessage.textContent = 'Must match the password above.';
                    matchMessage.className = 'hint';
                    confirmPassword.classList.remove('input-error');
                    return;
                }
                if (password.value === confirmPassword.value) {
                    matchMessage.textContent = 'Passwords match.';
                    matchMessage.className = 'hint hint-success';
                    confirmPassword.classList.remove('input-error');
                } else {
                    matchMessage.textContent = 'Passwords do not match.';
                    matchMessage.className = 'hint hint-error';
                    confirmPassword.classList.add('input-error');
                }
            }

            password.addEventListener('input', checkMatch);
            confirmPassword.addEventListener('input', checkMatch);

            // Submit to the backend
            document.getElementById('registerForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                if (password.value !== confirmPassword.value) {
                    checkMatch();
                    confirmPassword.focus();
                    return;
                }

                const payload = {
                    username: document.getElementById('username').value.trim(),
                    password: password.value,
                    confirmPassword: confirmPassword.value
                };

                const submitBtn = document.querySelector('.btn-primary');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creating account...';

                try {
                    console.log('Sending payload:', payload);

                    const response = await fetch('register.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                   console.log('Response status:', response.status);

const rawText = await response.text();
console.log('Raw response:', rawText);
const result = JSON.parse(rawText);
console.log('Response data:', result);

                    if (result.success) {
                        alert('Account created successfully! Please log in.');
                        window.location.href = 'login.html';
                    } else {
                        alert(result.error || 'Could not create account.');
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                    alert('Something went wrong while creating your account. Please check the console for details.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Account';
                }
            });
        });
    } catch (err) {
        console.error("[script.js] error in login/js/create-account.js:", err);
    }
})();

/* ---- student-side-main/js/calendar.js ---- */
(function () {
    try {
        let eventsByDate = {};

        function formatTime(time24) {
            const [hour, minute] = time24.split(':');
            const h = parseInt(hour);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const displayHour = h % 12 === 0 ? 12 : h % 12;
            return `${displayHour}:${minute} ${ampm}`;
        }

        function categoryToClassName(category) {
            if (category === "ACADEMIC") return "green";
            if (category === "NON-ACADEMIC") return "yellow";
            if (category === "CAREER") return "blue";
            return "";
        }

        function categoryToDisplayName(category) {
            if (category === "ACADEMIC") return "Academic";
            if (category === "NON-ACADEMIC") return "Non-Academic";
            if (category === "CAREER") return "Career";
            return category;
        }

        fetch('get-events.php')
            .then(res => res.json())
            .then(data => {
                eventsByDate = {};
                data.forEach(event => {
                    if (!eventsByDate[event.date]) {
                        eventsByDate[event.date] = [];
                    }
                    eventsByDate[event.date].push(event);
                });
                renderCalendar();
            })
            .catch(err => {
                console.error('Failed to load events:', err);
                renderCalendar(); // still render an empty calendar rather than nothing
            });

        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
                dropdownMenu.classList.remove("show");
            }
        });


        const grid = document.getElementById("calendarGrid");
        const monthTitle = document.getElementById("monthTitle");
        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        const months = [
            "January","February","March",
            "April","May","June",
            "July","August","September",
            "October","November","December"
        ];

        function renderCalendar(){

            grid.innerHTML = "";

            monthTitle.textContent =
                `${months[currentMonth]} ${currentYear}`;

            const daysHeader = ["Su","Mo","Tu","We","Th","Fr","Sa"];

            daysHeader.forEach((day,index)=>{

                const div = document.createElement("div");
                div.classList.add("day-header");

                if(index===0){
                    div.classList.add("sunday");
                }

                div.textContent = day;
                grid.appendChild(div);

            });

            const firstDay =
                new Date(currentYear,currentMonth,1).getDay();

            const daysInMonth =
                new Date(currentYear,currentMonth+1,0).getDate();

            for(let i=0;i<firstDay;i++){

                const blank=document.createElement("div");
                grid.appendChild(blank);

            }

            for(let day=1;day<=daysInMonth;day++){

                const cell=document.createElement("div");
                cell.classList.add("day-cell");

                if(
                    day===today.getDate() &&
                    currentMonth===today.getMonth() &&
                    currentYear===today.getFullYear()
                ){
                    cell.classList.add("today");
                }

                cell.innerHTML=`<div class="day-number">${day}</div>`;

                // Show events only in the current month/year
                const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                if (eventsByDate[dateKey]) {
                    eventsByDate[dateKey].forEach(event => {
                        const dot = document.createElement("div");
                        dot.classList.add("event-dot");
                        dot.classList.add(categoryToClassName(event.category));
                        cell.appendChild(dot);
                    });
                }

                cell.addEventListener("click",()=>openModal(day));

                grid.appendChild(cell);

            }

        }
        const modal = document.getElementById("eventModal");
        const modalDate = document.getElementById("modalDate");
        const modalEvents = document.getElementById("modalEvents");
        const closeBtn = document.querySelector(".close-btn");

        function openModal(day){

            modal.classList.add("show");

            modalDate.textContent =`${months[currentMonth]} ${day}, ${currentYear}`;

            modalEvents.innerHTML = "";

            const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayEvents = eventsByDate[dateKey];

            if (!dayEvents || dayEvents.length === 0) {
                modalEvents.innerHTML = "<p>No events scheduled.</p>";
                return;
            }

            dayEvents.forEach(event => {
                modalEvents.innerHTML += `
                    <div class="event-card">
                        <h4>${event.title}</h4>
                        <p><strong>Category:</strong> ${categoryToDisplayName(event.category)}</p>
                        <p><strong>Time:</strong> ${formatTime(event.startTime)}</p>
                        <p><strong>Location:</strong> ${event.location}</p>
                        <button class="view-event-btn" onclick="window.location.href='events.php?id=${event.id}'">
                            View Event
                        </button>
                    </div>
                `;
            });

        }

        document.getElementById("prevMonth").onclick = () => {
            modal.classList.remove("show");

            currentMonth--;

            if(currentMonth < 0){
                currentMonth = 11;
                currentYear--;
            }

            renderCalendar();
        };

        document.getElementById("nextMonth").onclick = () => {
            modal.classList.remove("show");

            currentMonth++;

            if(currentMonth > 11){
                currentMonth = 0;
                currentYear++;
            }

            renderCalendar();
        };

        closeBtn.onclick = () => {
            modal.classList.remove("show");
        };

        window.onclick = (e) => {
            if (e.target === modal) {
                modal.classList.remove("show");
            }
        };
    } catch (err) {
        console.error("[script.js] error in student/js/calendar.js:", err);
    }
})();

/* ---- student-side-main/js/dashboard.js ---- */
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

        let events = [];
        let currentIndex = 0;

        function renderCarousel() {

            container.innerHTML = "";

            if (events.length === 0) {
                container.innerHTML = `
                    <div class="event-card">
                        <h3>No Events Yet</h3>
                        <p>Add events from the Events page</p>
                    </div>
                `;
                return;
            }

            for (let i = 0; i < 2; i++) {

                const index = currentIndex + i;

                if (index >= events.length) break;

                const event = events[index];

                container.innerHTML += `
                    <div class="event-card" onclick="location.href='events.php?id=${event.id}'">
                        <h3>${event.title}</h3>
                        <p>${event.category}</p>
                        <small>${event.date}</small>
                    </div>
                `;
            }
        }

        fetch("api/get-interested-events.php")
            .then(res => res.json())
            .then(data => {
                events = data;
                renderCarousel();
            })
            .catch(err => console.error(err));

        nextBtn.addEventListener("click", () => {
            if (currentIndex + 2 < events.length) {
                currentIndex += 2;
                renderCarousel();
            }
        });

        prevBtn.addEventListener("click", () => {
            if (currentIndex - 2 >= 0) {
                currentIndex -= 2;
                renderCarousel();
            }
        });
    

        const chartCanvas = document.getElementById("studentChart");

        fetch("api/get-category-stats.php")
        .then(res => res.json())
        .then(data => {

            const labels = data.map(item => item.CATEGORY);
            const values = data.map(item => item.total);

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

/* ---- student-side-main/js/events.js ---- */
(function () {
    try {
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const params = new URLSearchParams(window.location.search);
        const eventId = parseInt(params.get("id"));
        const categoryParam = params.get("category");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", () => {
            dropdownMenu.classList.remove("show");
        });


        let eventsData = [];
        let selectedEvent = null;

        fetch("api/get-events.php")
        .then(res => res.json())
        .then(data => {
            eventsData = data;
            clearFilters();
        })
        .catch(error => {
            console.error(error);
            showNoEvent();
        });

        function renderSidebar(events) {
            const sidebar = document.getElementById("eventSidebar");
            sidebar.innerHTML = "";

            if (events.length === 0) {
                sidebar.innerHTML = `
                    <div class="no-events">
                        No Events Available
                    </div>
                `;
                return;
            }

            events.forEach((event, index) => {

                const btn = document.createElement("button");
                btn.className = "event-item";

                if (index === 0) {
                    btn.classList.add("active");
                }

                btn.textContent = event.title;
                btn.dataset.id = event.id;

                btn.addEventListener("click", () => {

                    document.querySelectorAll(".event-item").forEach(b =>
                        b.classList.remove("active")
                    );

                    btn.classList.add("active");

                    selectedEvent = event;
                    showEventDetail(event);

                });

                sidebar.appendChild(btn);

            });  
        }

        function showEventDetail(event) {

           const interestedBtn = document.getElementById("interestedBtn");
            const isEnded = (event.status || "").toLowerCase() === "ended";

            if (isEnded && !event.isInterested) {
                interestedBtn.style.display = "none";
            } else {
            interestedBtn.style.display = "inline-block";
            updateInterestedButton(event.isInterested);
            }
    
            document.getElementById("postCommentBtn").style.display = "inline-block";

            document.getElementById("eventTitle").textContent =
                event.title || "-";

            document.getElementById("category").textContent =
                event.category || "-";
    
            document.getElementById("eventDate").textContent =
                formatDate(event.date);
    
            document.getElementById("duration").textContent =
                `${formatTime(event.startTime)} - ${formatTime(event.endTime)}`;

            document.getElementById("venue").textContent =
                event.venue || event.location || "-";

            document.getElementById("status").textContent =
                event.status || "-";

            document.getElementById("registration").textContent =
                event.registration || "-";

            document.getElementById("organizer").textContent =
                event.organizer || "-";

            document.getElementById("description").textContent =
                event.description || "No description available.";

            renderImageCarousel(event.images || []);

            loadComments(event.id);
        }

        function loadComments(eventId) {
            fetch(`get-comments.php?event_id=${eventId}`)
                .then(res => res.json())
                .then(comments => renderCommentsCarousel(comments))
                .catch(err => {
                    console.error('Failed to load comments:', err);
                    renderCommentsCarousel([]);
                });
        }
    
            /*fetch(`get-comments.php?event_id=${event.id}`)
                .then(res => res.json())
                .then(comments => renderCommentsCarousel(comments))
                .catch(err => {
                    console.error('Failed to load comments:', err);
                    renderCommentsCarousel([]);
                });
            }*/

        let currentImageIndex = 0;
        let currentCommentIndex = 0;
        let commentsIntervalId = null;

        function renderCommentsCarousel(comments) {
            const track = document.getElementById('commentsTrack');
            const dotsContainer = document.getElementById('commentsDots');
            track.innerHTML = '';
            dotsContainer.innerHTML = '';
            currentCommentIndex = 0;

            if (commentsIntervalId) {
                clearInterval(commentsIntervalId);
                commentsIntervalId = null;
            }

            if (!comments || comments.length === 0) {
                track.innerHTML = '<div class="carousel-slide comment-slide"><p class="comment-text">No comments yet.</p></div>';
                return;
            }

            comments.forEach((c) => {
                const slide = document.createElement('div');
                slide.className = 'carousel-slide comment-slide';

                const editBtnHtml = c.isOwner
                    ? `<button class="comment-edit-btn" data-id="${c.id}" title="Edit comment">&#9998;</button>`
                    : '';

                slide.innerHTML = `${editBtnHtml}<p class="comment-text">"${c.text}"</p><p class="comment-author">- ${c.author}</p>`;
                track.appendChild(slide);

                if (c.isOwner) {
                    slide.querySelector('.comment-edit-btn').addEventListener('click', () => {
                        openEditCommentModal(c.id, c.text);
                    });
                }
            });

            comments.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dotsContainer.appendChild(dot);
            });

            updateCommentsTrack();

            if (comments.length > 1) {
                commentsIntervalId = setInterval(() => {
                    currentCommentIndex = (currentCommentIndex + 1) % comments.length;
                    updateCommentsTrack();
                }, 3000);
            }
        }
    
        function renderImageCarousel(images) {
            const track = document.getElementById('imageTrack');
            const dotsContainer = document.getElementById('imageDots');
            const prevBtn = document.getElementById('imgPrev');
            const nextBtn = document.getElementById('imgNext');
            track.innerHTML = '';
            dotsContainer.innerHTML = '';
            currentImageIndex = 0;

            if (!images || images.length === 0) {
                track.innerHTML = '<div class="carousel-slide">No images</div>';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                return;
            }

            images.forEach((src, i) => {
                const slide = document.createElement('div');
                slide.className = 'carousel-slide';
                slide.innerHTML = `<img src="${src}" alt="Event image ${i + 1}">`;
                track.appendChild(slide);

                const dot = document.createElement('button');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => goToImage(i));
                dotsContainer.appendChild(dot);
            });

            const showControls = images.length > 1;
            prevBtn.style.display = showControls ? 'flex' : 'none';
            nextBtn.style.display = showControls ? 'flex' : 'none';
            dotsContainer.style.display = showControls ? 'flex' : 'none';

            updateImageTrack();
        }

        function goToImage(index) {
            const track = document.getElementById('imageTrack');
            const total = track.children.length;
            currentImageIndex = (index + total) % total;
            updateImageTrack();
        }

        function updateImageTrack() {
            const track = document.getElementById('imageTrack');
            track.style.transform = `translateX(-${currentImageIndex * 100}%)`;
            document.querySelectorAll('#imageDots .dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === currentImageIndex);
            });
        }

        document.getElementById('imgPrev').addEventListener('click', () => goToImage(currentImageIndex - 1));
        document.getElementById('imgNext').addEventListener('click', () => goToImage(currentImageIndex + 1));

        /*function renderCommentsCarousel(comments) {
            const track = document.getElementById('commentsTrack');
            const dotsContainer = document.getElementById('commentsDots');
            track.innerHTML = '';
            dotsContainer.innerHTML = '';
            currentCommentIndex = 0;

            if (commentsIntervalId) {
                clearInterval(commentsIntervalId);
                commentsIntervalId = null;
            }

            if (!comments || comments.length === 0) {
                track.innerHTML = '<div class="carousel-slide comment-slide"><p class="comment-text">No comments yet.</p></div>';
                return;
            }

            comments.forEach((c) => {
                const slide = document.createElement('div');
                slide.className = 'carousel-slide comment-slide';
                slide.innerHTML = `<p class="comment-text">"${c.text}"</p><p class="comment-author">- ${c.author}</p>`;
                track.appendChild(slide);
            });

            comments.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dotsContainer.appendChild(dot);
            });

            updateCommentsTrack();

            if (comments.length > 1) {
                commentsIntervalId = setInterval(() => {
                    currentCommentIndex = (currentCommentIndex + 1) % comments.length;
                    updateCommentsTrack();
                }, 3000 ); // just change if you want comments to go faster (ms)
            }
        }*/

        function updateCommentsTrack() {
            const track = document.getElementById('commentsTrack');
            track.style.transform = `translateX(-${currentCommentIndex * 100}%)`;
            document.querySelectorAll('#commentsDots .dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === currentCommentIndex);
            });
        }

        /* carousel stuff is up here in case I can't find it */

        function formatDate(dateString) {

            if (!dateString) return "-";

            const date = new Date(dateString);

            return date.toLocaleDateString("en-PH", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric"
            });

        }

        function formatTime(time24) {

            if (!time24)
                return "-";

            const [hour, minute] = time24.split(":");

            const h = parseInt(hour);

            const ampm = h >= 12 ? "PM" : "AM";

            const displayHour = h % 12 === 0 ? 12 : h % 12;

            return `${displayHour}:${minute} ${ampm}`;
        }

        document.getElementById("interestedBtn").addEventListener("click", async () => {

            if (!selectedEvent) return;

            try {

                const response = await fetch("api/add-interest.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        event_id: selectedEvent.id
                    })
                });

                const result = await response.json();

                if (result.success) {
                    selectedEvent.isInterested = result.interested;
                    updateInterestedButton(selectedEvent.isInterested);
                }

                showAlert("Interested Events", result.message);

            } catch (err) {
                console.error(err);
                alert("Unable to update interest.");
            }

        });

        function updateInterestedButton(isInterested) {
            const btn = document.getElementById("interestedBtn");
            btn.classList.toggle("interested-active", isInterested);
            btn.dataset.interested = isInterested ? "true" : "false";
            setInterestedLabel(btn);
        }

        function setInterestedLabel(btn) {
            const isInterested = btn.dataset.interested === "true";
            btn.textContent = isInterested ? "Interested ✓" : "Interested!";
        }

        document.getElementById("interestedBtn").addEventListener("mouseenter", () => {
            const btn = document.getElementById("interestedBtn");
            const isInterested = btn.dataset.interested === "true";
            btn.textContent = isInterested ? "Remove Interest?" : "Mark Interested?";
        });

        document.getElementById("interestedBtn").addEventListener("mouseleave", () => {
            setInterestedLabel(document.getElementById("interestedBtn"));
        });


        const commentModalOverlay = document.getElementById('commentModalOverlay');
        const commentForm = document.getElementById('commentForm');
        const postCommentBtn = document.getElementById('postCommentBtn');
        const cancelCommentBtn = document.getElementById('cancelCommentBtn');
        const commenterNameEl = document.getElementById('commenterName');
        const anonToggle = document.getElementById('anonToggle');

        function getLoggedInUsername() {
            return currentUsername || 'Student Name';
        }

        postCommentBtn.addEventListener('click', () => {
            commenterNameEl.textContent = getLoggedInUsername();
            anonToggle.checked = false;
            commentModalOverlay.classList.add('show');
        });

        cancelCommentBtn.addEventListener('click', closeCommentModal);

        commentModalOverlay.addEventListener('click', (e) => {
            if (e.target === commentModalOverlay) closeCommentModal();
        });

        function closeCommentModal() {
            commentModalOverlay.classList.remove('show');
            commentForm.reset();
        }

        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const isAnonymous = anonToggle.checked;
            const username = getLoggedInUsername();

            const newComment = {
                event_id: selectedEvent.id,
                author: isAnonymous ? 'Anonymous' : username,
                posted_by: username,
                is_anonymous: isAnonymous,
                text: document.getElementById('commentMessage').value.trim(),
                created_at: new Date().toISOString()
            };

            if (!newComment.text) return;

            try {
                const response = await fetch('submit-comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(newComment)
                });

                const result = await response.json();

                if (result.success) {
                    loadComments(selectedEvent.id);
                } else {
                    showAlert("Error", result.error || "Failed to post comment.");
                }

            } catch (err) {
                console.error('Failed to submit comment:', err);
                showAlert("Error", "Something went wrong posting your comment.");
            }

            closeCommentModal();
        });
        /*
        function addCommentLocally(comment) {
            if (!selectedEvent.comments) selectedEvent.comments = [];
            selectedEvent.comments.push(comment);
            renderCommentsCarousel(selectedEvent.comments);
        }*/

        const editCommentModalOverlay = document.getElementById('editCommentModalOverlay');
        const editCommentForm = document.getElementById('editCommentForm');
        const editCommentMessage = document.getElementById('editCommentMessage');
        const cancelEditCommentBtn = document.getElementById('cancelEditCommentBtn');
        const deleteCommentBtn = document.getElementById('deleteCommentBtn');

        let editingCommentId = null;

        function openEditCommentModal(commentId, currentText) {
            editingCommentId = commentId;
            editCommentMessage.value = currentText;
            editCommentModalOverlay.classList.add('show');
        }

        function closeEditCommentModal() {
            editCommentModalOverlay.classList.remove('show');
            editCommentForm.reset();
            editingCommentId = null;
        }

        cancelEditCommentBtn.addEventListener('click', closeEditCommentModal);

        editCommentModalOverlay.addEventListener('click', (e) => {
            if (e.target === editCommentModalOverlay) closeEditCommentModal();
        });

        editCommentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = editCommentMessage.value.trim();
            if (!text || !editingCommentId) return;

            try {
                const response = await fetch('edit-comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ comment_id: editingCommentId, text })
                });
                const result = await response.json();

                if (result.success) {
                    loadComments(selectedEvent.id);
                } else {
                    showAlert("Error", result.error || "Failed to update comment.");
                }
            } catch (err) {
                console.error('Failed to edit comment:', err);
                showAlert("Error", "Something went wrong updating your comment.");
            }

            closeEditCommentModal();
        });

        deleteCommentBtn.addEventListener('click', async () => {
            if (!editingCommentId) return;
            if (!confirm('Delete this comment? This cannot be undone.')) return;

            try {
                const response = await fetch('delete-comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ comment_id: editingCommentId })
                });
                const result = await response.json();

                if (result.success) {
                    loadComments(selectedEvent.id);
                } else {
                    showAlert("Error", result.error || "Failed to delete comment.");
                }
            } catch (err) {
                console.error('Failed to delete comment:', err);
                showAlert("Error", "Something went wrong deleting your comment.");
            }

            closeEditCommentModal();
        });

        function showNoEvent() {
            document.getElementById("eventTitle").textContent = "No Events Available";
            document.getElementById("category").textContent = "-";
            document.getElementById("eventDate").textContent = "-";
            document.getElementById("duration").textContent = "-";
            document.getElementById("venue").textContent = "-";
            document.getElementById("status").textContent = "-";
            document.getElementById("registration").textContent = "-";
            document.getElementById("organizer").textContent = "-";
            document.getElementById("description").textContent =
                "There are currently no approved events.";
            document.getElementById("interestedBtn").style.display = "none";
            document.getElementById("postCommentBtn").style.display = "none";

            renderImageCarousel([]);
            renderCommentsCarousel([]);
        }

        const searchInput = document.getElementById("searchInput");

        searchInput.addEventListener("input", filterEvents);
        document.getElementById("categoryFilter")
            .addEventListener("change", filterEvents);
        document.getElementById("sortFilter")
            .addEventListener("change", filterEvents);
        document.getElementById("dateFilter")
            .addEventListener("change", filterEvents);
        document.getElementById("statusFilter")
            .addEventListener("change", filterEvents);

        function filterEvents() {

            const searchText = document.getElementById("searchInput").value
                .toLowerCase()
                .trim();

           const category = document.getElementById("categoryFilter").value;
        const sort = document.getElementById("sortFilter").value;
        const selectedDate = document.getElementById("dateFilter").value;
        const status = document.getElementById("statusFilter").value;
            let filtered = [...eventsData];

            if (searchText !== "") {
               filtered = filtered.filter(event =>
            (event.title || "").toLowerCase().includes(searchText) ||
            (event.organizer || "").toLowerCase().includes(searchText) ||
            (event.venue || "").toLowerCase().includes(searchText) ||
            (event.description || "").toLowerCase().includes(searchText)
        );
            }
            if (selectedDate !== "") {
            filtered = filtered.filter(event => event.date === selectedDate);
        }

            if (category !== "All Event Categories") {
                filtered = filtered.filter(event =>
                    event.category.toLowerCase() === category.toLowerCase()
                );
            }
             if (status !== "All Event Status") {
            filtered = filtered.filter(event =>
                (event.status || "").toLowerCase() === status.toLowerCase()
            );
        } else {
            filtered = filtered.filter(event =>
                (event.status || "").toLowerCase() !== "ended"
            );
        }

            filtered.sort((a, b) => {
                if (sort === "Newest") {
                    return new Date(b.date) - new Date(a.date);
                } else {
                    return new Date(a.date) - new Date(b.date);
                }
   
            });

            renderSidebar(filtered);

        if (filtered.length > 0) {

            let eventToShow;

            if (eventId) {
                eventToShow = filtered.find(e => Number(e.id) === eventId);
            }

            if (!eventToShow) {
                eventToShow = filtered[0];
            }

            selectedEvent = eventToShow;
            showEventDetail(eventToShow);

            document.querySelectorAll(".event-item").forEach(btn => {
                btn.classList.toggle(
                    "active",
                    Number(btn.dataset.id) === Number(eventToShow.id)
                );
            });

        } else {
            showNoEvent();
        }
        }
        function clearFilters() {
            document.getElementById("searchInput").value = "";
            document.getElementById("dateFilter").value = "";
            document.getElementById("categoryFilter").value =
            categoryParam ? categoryParam : "All Event Categories";
            document.getElementById("sortFilter").value = "Oldest";
            document.getElementById("statusFilter").value = "All Event Status";

            filterEvents();
        }

        document.getElementById("clearFiltersBtn")
            .addEventListener("click", clearFilters);

        const alertModal = document.getElementById("alertModal");
        const alertTitle = document.getElementById("alertTitle");
        const alertMessage = document.getElementById("alertMessage");
        const alertOkBtn = document.getElementById("alertOkBtn");

        function showAlert(title, message) {
            alertTitle.textContent = title;
            alertMessage.textContent = message;
            alertModal.classList.add("show");
        }

        function closeAlert() {
            alertModal.classList.remove("show");
        }

        alertOkBtn.addEventListener("click", closeAlert);

        alertModal.addEventListener("click", (e) => {
            if (e.target === alertModal) {
                closeAlert();
            }
        });
    } catch (err) {
        console.error("[script.js] error in student/js/events.js:", err);
    }
})();

/* ---- student-side-main/js/edit-profile.js ---- */
(function () {
    try {
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click", (event) => {
            event.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", (event) => {
            if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove("show");
            }
        });

        const alertModal = document.getElementById("alertModal");
        const alertTitle = document.getElementById("alertTitle");
        const alertMessage = document.getElementById("alertMessage");
        const alertOkBtn = document.getElementById("alertOkBtn");

        function showAlert(title, message) {
            alertTitle.textContent = title;
            alertMessage.textContent = message;
            alertModal.classList.add("show");
        }

        function closeAlert() {
            alertModal.classList.remove("show");
        }

        alertOkBtn.addEventListener("click", closeAlert);

        alertModal.addEventListener("click", (e) => {
            if (e.target === alertModal) closeAlert();
        });

        const passwordInput = document.getElementById("password");

        function setupPasswordToggle(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);

            button.addEventListener("click", () => {
                if (input.type === "password") {
                    input.type = "text";
                    button.textContent = "Hide";
                } else {
                    input.type = "password";
                    button.textContent = "Show";
                }
            });
        }

        setupPasswordToggle("password", "togglePassword");
        setupPasswordToggle("confirmPassword", "toggleConfirmPassword");

        const profileImage = document.getElementById("profileImage");
        const profilePreview = document.getElementById("profilePreview");

        profileImage.addEventListener("change", () => {
            const file = profileImage.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                profilePreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        });

        document.getElementById("updateBtn").addEventListener("click", async () => {
            const username = document.getElementById("username").value.trim();
            const password = passwordInput.value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            if (!username) {
            showAlert("Notice", "Username cannot be empty.");
            return;
        }
        if (password !== confirmPassword) {
            showAlert("Notice", "Passwords do not match.");
            return;
        }
            const formData = new FormData();
            formData.append("username", username);
            formData.append("password", password);
            const file = profileImage.files[0];
            if (file) {
                formData.append("profileImage", file);
            }
            try {
                const response = await fetch("update-profile.php", {
                    method: "POST",
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
            showAlert("Profile Updated", "Profile updated successfully.");
            setTimeout(() => window.location.href = "dashboard.php", 1200);
        } else {
            showAlert("Update Failed", result.error || "Unknown error");
        }
            } catch (err) {
            console.error("Update error:", err);
            showAlert("Error", "Something went wrong while updating your profile.");
        }
        });
    } catch (err) {
        console.error("[script.js] error in student/js/edit-profile.js:", err);
    }
})();

/* ---- admin-side-main/js/account-management.js ---- */
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
    
            const phpMsg = document.getElementById("php-success-msg");
            if (phpMsg) {
                showSuccessModal(phpMsg.dataset.message);
            }
        });
    } catch (err) {
        console.error("[script.js] error in admin/js/account-management.js:", err);
    }
})();

/* ---- admin-side-main/js/add-comment.js ---- */
(function () {
    try {
        if (!document.body.classList.contains("s-admin-add-comment")) return;
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
            window.location.href = 'comments-management.php';
        }

        window.addEventListener('load', () => {
            const successMsg = document.getElementById('php-success-msg')?.dataset.message;
            if (successMsg) {
                showSuccessModal(successMsg);
            }
        });
    } catch (err) {
        console.error("[script.js] error in admin/js/add-comment.js:", err);
    }
})();

/* ---- admin-side-main/js/add-event.js ---- */
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

        window.addEventListener('load', () => {
            const successMsg = document.getElementById('php-success-msg')?.dataset.message;
            if (successMsg) {
                showSuccessModal(successMsg);
            }
        });
    } catch (err) {
        console.error("[script.js] error in admin/js/add-event.js:", err);
    }
})();

/* ---- admin-side-main/js/add-student.js ---- */
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

/* ---- admin-side-main/js/admin-create.js ---- */
(function () {
    try {
        if (!document.body.classList.contains("s-admin-admin-create")) return;
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click",(e)=>{
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click",(e)=>{
            if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target) ){
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
    } catch (err) {
        console.error("[script.js] error in admin/js/admin-create.js:", err);
    }
})();

/* ---- admin-side-main/js/admin-dashboard.js ---- */
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

        // Success message modal
        const successModal = document.getElementById("successModal");
        const closeSuccessBtn = document.getElementById("closeSuccessBtn");

        if (closeSuccessBtn && successModal) {
            closeSuccessBtn.addEventListener("click", () => {
                successModal.classList.remove("show");
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const phpMsg = document.getElementById("php-success-msg");
            if (phpMsg && successModal) {
                document.getElementById("successModalMessage").textContent = phpMsg.dataset.message;
                successModal.classList.add("show");
            }
        });

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

/* ---- admin-side-main/js/admin-event-review.js ---- */
(function () {
    try {
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");


        profileBtn.addEventListener("click",(e)=>{ e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click",(e)=>{
            if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
                dropdownMenu.classList.remove("show");
            }
        });

        // Carousel
        let currentImageIndex = 0;

        function renderImageCarousel(images){
            const track = document.getElementById('imageTrack');
            const dotsContainer = document.getElementById('imageDots');
            const prevBtn = document.getElementById('imgPrev');
            const nextBtn = document.getElementById('imgNext');
            track.innerHTML = '';
            dotsContainer.innerHTML = '';
            currentImageIndex = 0;

            if(!images || images.length === 0){
                track.innerHTML = '<div class="carousel-slide">No images</div>';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                return;
            }

            images.forEach((src,i)=>{
                const slide = document.createElement('div');
                slide.className = 'carousel-slide';
                slide.innerHTML = `<img src="${src}" alt="Event image ${i + 1}">`;
                track.appendChild(slide);

                const dot = document.createElement('button');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => goToImage(i));
                dotsContainer.appendChild(dot);
            });

            const showControls = images.length > 1;
            prevBtn.style.display = showControls ? 'flex' : 'none';
            nextBtn.style.display = showControls ? 'flex' : 'none';
            dotsContainer.style.display = showControls ? 'flex' : 'none';

            updateImageTrack();
        }

        function goToImage(index){
            const track = document.getElementById('imageTrack');
            const total = track.children.length;
            currentImageIndex = (index + total) % total;
            updateImageTrack();
        }

        function updateImageTrack(){
            const track = document.getElementById('imageTrack');
            track.style.transform = `translateX(-${currentImageIndex * 100}%)`;
            document.querySelectorAll('#imageDots .dot').forEach((dot,i)=>{
                dot.classList.toggle('active', i === currentImageIndex);
            });
        }

        document.getElementById('imgPrev').addEventListener('click', () => goToImage(currentImageIndex - 1));
        document.getElementById('imgNext').addEventListener('click', () => goToImage(currentImageIndex + 1));

        renderImageCarousel(eventImages);
    } catch (err) {
        console.error("[script.js] error in admin/js/admin-event-review.js:", err);
    }
})();

/* ---- admin-side-main/js/comments-management.js ---- */
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
    } catch (err) {
        console.error("[script.js] error in admin/js/comments-management.js:", err);
    }
})();

/* ---- org-side-main/js/create.js ---- */
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
        const fileInput = document.getElementById("eventImage");
        const uploadIcon = document.querySelector(".upload-icon");

        uploadBox.addEventListener("click",()=>{
            fileInput.click();
        });

        fileInput.addEventListener("change", () => {
            const oldChip = uploadBox.querySelector(".file-chip");
            if (oldChip) oldChip.remove();

            if (fileInput.files.length > 0) {
                const chip = document.createElement("div");
                chip.className = "file-chip";

                const nameSpan = document.createElement("span");
                nameSpan.textContent = fileInput.files[0].name;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "×";
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    fileInput.value = "";
                    chip.remove();
                });

                chip.appendChild(nameSpan);
                chip.appendChild(removeBtn);
                uploadBox.insertBefore(chip, uploadIcon);
            }
        });

        const form = document.querySelector(".form-card");

        document.getElementById("clearBtn").addEventListener("click",()=>{
            form.querySelectorAll("input, textarea, select").forEach(field=>{
                if(field.type !== "file"){
                    field.value = "";
                }
            });
        });
    } catch (err) {
        console.error("[script.js] error in org/js/create.js:", err);
    }
})();

/* ---- org-side-main/js/edit-event.js ---- */
(function () {
    try {
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove("show");
            }
        });

        const uploadBox = document.querySelector(".upload-box");
        const fileInput = document.getElementById("eventImage");
        const uploadIcon = document.querySelector(".upload-icon");
        const removeImageFlag = document.getElementById("removeImageFlag");

        uploadBox.addEventListener("click", () => {
            fileInput.click();
        });

        function attachRemoveHandler(btn, chip, isExisting) {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                chip.remove();
                fileInput.value = "";
                if (isExisting && removeImageFlag) {
                    removeImageFlag.value = "1";
                }
            });
        }

        const existingChip = document.getElementById("existingImageChip");
        if (existingChip) {
            const existingRemoveBtn = existingChip.querySelector("#removeFile");
            if (existingRemoveBtn) {
                attachRemoveHandler(existingRemoveBtn, existingChip, true);
            }
        }

        fileInput.addEventListener("change", () => {
            const oldChip = uploadBox.querySelector(".file-chip");
            if (oldChip) oldChip.remove();

            if (fileInput.files.length > 0) {
                if (removeImageFlag) {
                    removeImageFlag.value = "0"; 
                }

                const chip = document.createElement("div");
                chip.className = "file-chip";

                const nameSpan = document.createElement("span");
                nameSpan.textContent = fileInput.files[0].name;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "×";

                chip.appendChild(nameSpan);
                chip.appendChild(removeBtn);
                uploadBox.insertBefore(chip, uploadIcon);

                attachRemoveHandler(removeBtn, chip, false);
            }
        });

        document.getElementById("deleteBtn").addEventListener("click", async () => {
            const confirmed = await showConfirmModal(
                "Are you sure you want to delete this event?",
                { confirmText: "Delete", cancelText: "Cancel", danger: true, title: "Delete event" }
            );

            if (!confirmed) return;

            const eventIdInput = document.querySelector('input[name="event_id"]');
            const eventId = eventIdInput ? eventIdInput.value : "";

            try {
                const response = await fetch("delete-event-process.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "event_id=" + encodeURIComponent(eventId),
                });

                let data;
                try {
                    data = await response.json();
                } catch {
                    data = null;
                }

                if (!response.ok || !data || !data.success) {
                    await showModal(
                        (data && data.error) || "Something went wrong while deleting the event.",
                        { type: "error" }
                    );
                    return;
                }

                window.location.href = "manage.php?deleted=1";
            } catch (err) {
                await showModal("Something went wrong while deleting the event.", { type: "error" });
            }
        });

        const commentsList = document.getElementById("commentsList");

        function renderComments(comments) {
            commentsList.innerHTML = "";

            if (comments.length === 0) {
                const empty = document.createElement("li");
                empty.className = "comments-empty";
                empty.textContent = "No comments yet on this event.";
                commentsList.appendChild(empty);
                return;
            }

            comments.forEach((comment) => {
                const item = document.createElement("li");
                item.className = "comment-item";
                item.dataset.commentId = comment.id;

                const textWrap = document.createElement("div");
                textWrap.className = "comment-text-wrap";

                const author = document.createElement("span");
                author.className = "comment-author";
                author.textContent = comment.author;

                const text = document.createElement("p");
                text.className = "comment-text";
                text.textContent = comment.text;

                textWrap.appendChild(author);
                textWrap.appendChild(text);

                const deleteBtn = document.createElement("button");
                deleteBtn.type = "button";
                deleteBtn.className = "comment-delete-btn";
                deleteBtn.textContent = "Delete";
                deleteBtn.addEventListener("click", () => deleteComment(comment.id, item));

                item.appendChild(textWrap);
                item.appendChild(deleteBtn);
                commentsList.appendChild(item);
            });
        }

        async function loadComments() {
            try {
                const response = await fetch("get-event-comments.php?event_id=" + encodeURIComponent(currentEventId));
                const data = await response.json();

                if (!response.ok || !data.success) {
                    commentsList.innerHTML = "<li class=\"comments-empty\">Couldn't load comments.</li>";
                    return;
                }

                renderComments(data.comments);
            } catch (err) {
                commentsList.innerHTML = "<li class=\"comments-empty\">Couldn't load comments.</li>";
            }
        }

        async function deleteComment(commentId, itemEl) {
            const confirmed = await showConfirmModal(
                "Are you sure you want to delete this comment?",
                { confirmText: "Delete", cancelText: "Cancel", danger: true, title: "Delete comment" }
            );

            if (!confirmed) return;

            try {
                const response = await fetch("delete-comment-process.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ comment_id: commentId }),
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    await showModal((data && data.error) || "Something went wrong while deleting the comment.", { type: "error" });
                    return;
                }

                itemEl.remove();
                if (!commentsList.querySelector(".comment-item")) {
                    renderComments([]);
                }
            } catch (err) {
                await showModal("Something went wrong while deleting the comment.", { type: "error" });
            }
        }

        loadComments();
    } catch (err) {
        console.error("[script.js] error in org/js/edit-event.js:", err);
    }
})();

/* ---- org-side-main/js/edit-organization.js ---- */
(function () {
    try {
        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove("show");
            }
        });

        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", () => {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePassword.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                togglePassword.textContent = "Show";
            }
        });

        const profileImage = document.getElementById("profileImage");
        const profilePreview = document.getElementById("profilePreview");
        const uploadPicBtn = document.getElementById("uploadPicBtn");

        uploadPicBtn.addEventListener("click", () => {
            profileImage.click();
        });

        profileImage.addEventListener("change", () => {
            const file = profileImage.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                profilePreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        });

        const formError = document.getElementById("formError");

        function showError(message) {
            formError.textContent = message;
            formError.style.display = "block";
        }

        function clearError() {
            formError.textContent = "";
            formError.style.display = "none";
        }

        document.getElementById("updateBtn").addEventListener("click", async () => {
            clearError();

            const orgName = document.getElementById("orgName").value.trim();
            const password = passwordInput.value;

            if (!orgName || !password) {
                showError("Organization name and password cannot be empty.");
                return;
            }

            const formData = new FormData();
            formData.append("orgName", orgName);
            formData.append("password", password);

            const file = profileImage.files[0];
            if (file) {
                formData.append("profileImage", file);
            }

            const updateBtn = document.getElementById("updateBtn");
            updateBtn.disabled = true;
            updateBtn.textContent = "Updating...";

            try {
                const response = await fetch("update-organization-process.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    await showModal("Organization details updated successfully!");
                    location.reload();
                } else {
                    showError(result.error || "Update failed. Please try again.");
                }
            } catch (err) {
                console.error("Update error:", err);
                showError("Something went wrong while updating your organization details.");
            } finally {
                updateBtn.disabled = false;
                updateBtn.textContent = "Update Details";
            }
        });
    } catch (err) {
        console.error("[script.js] error in org/js/edit-organization.js:", err);
    }
})();

/* ---- org-side-main/js/manage.js ---- */
(function () {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get("updated") === "1") {
            const message = urlParams.get("resubmitted") === "1"
                ? "Event updated and resubmitted for admin approval."
                : "Event updated successfully!";
            showModal(message).then(() => {});
            urlParams.delete("updated");
            urlParams.delete("resubmitted");
            const newQuery = urlParams.toString();
            history.replaceState({}, "", window.location.pathname + (newQuery ? "?" + newQuery : ""));
        }

        if (urlParams.get("deleted") === "1") {
            showModal("Event deleted successfully!").then(() => {});
            urlParams.delete("deleted");
            const newQuery = urlParams.toString();
            history.replaceState({}, "", window.location.pathname + (newQuery ? "?" + newQuery : ""));
        }

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
    } catch (err) {
        console.error("[script.js] error in org/js/manage.js:", err);
    }
})();

/* ---- org-side-main/js/officer-dashboard.js ---- */
(function () {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get("created") === "1") {
            showModal("Event submitted successfully!").then(() => {});
            urlParams.delete("created");
            const newQuery = urlParams.toString();
            history.replaceState({}, "", window.location.pathname + (newQuery ? "?" + newQuery : ""));
        }

        const profileBtn = document.getElementById("profileBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if(!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)){
                dropdownMenu.classList.remove("show");
            }
        });

        function sum(arr) {
            return arr.reduce((total, n) => total + n, 0);
        }

        function markEmptyIfNoData(canvasEl, emptyEl, hasData) {
            if (!hasData) {
                canvasEl.classList.add("hide");
                emptyEl.classList.add("show");
            }
        }

        const categoryApprovalCtx = document.getElementById("categoryApprovalChart");

        new Chart(categoryApprovalCtx, {
            type: "bar",
            data: {
                labels: categoryLabels,
                datasets: [
                    {
                        label: "Approved",
                        data: categoryApprovedData,
                        backgroundColor: STATUS_COLORS.APPROVED,
                        borderRadius: 4,
                        maxBarThickness: 55
                    },
                    {
                        label: "Pending",
                        data: categoryPendingData,
                        backgroundColor: STATUS_COLORS.PENDING,
                        borderRadius: 4,
                        maxBarThickness: 55
                    },
                    {
                        label: "Rejected",
                        data: categoryRejectedData,
                        backgroundColor: STATUS_COLORS.REJECTED,
                        borderRadius: 4,
                        maxBarThickness: 55
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: "#eef2ef" }
                    }
                }
            }
        });
        markEmptyIfNoData(
            categoryApprovalCtx,
            document.getElementById("categoryApprovalChartEmpty"),
            sum([...categoryApprovedData, ...categoryPendingData, ...categoryRejectedData]) > 0
        );

        const locationCtx = document.getElementById("locationChart");

        new Chart(locationCtx, {
            type: "bar",
            data: {
                labels: locationLabels,
                datasets: [{
                    data: locationData,
                    backgroundColor: "#087f5b",
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                indexAxis: "y",
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: "#eef2ef" }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
        markEmptyIfNoData(locationCtx, document.getElementById("locationChartEmpty"), locationData.length > 0 && sum(locationData) > 0);

        const interestCtx = document.getElementById("interestChart");

        new Chart(interestCtx, {
            type: "bar",
            data: {
                labels: interestLabels,
                datasets: [{
                    data: interestData,
                    backgroundColor: "#2563a6",
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                indexAxis: "y",
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: "#eef2ef" }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
        markEmptyIfNoData(interestCtx, document.getElementById("interestChartEmpty"), interestData.length > 0 && sum(interestData) > 0);

        const scatterCtx = document.getElementById("scatterChart");

        const scatterColors = scatterPoints.map((p) => CATEGORY_COLORS[p.category] || "#8a9791");

        new Chart(scatterCtx, {
            type: "scatter",
            data: {
                datasets: [{
                    data: scatterPoints,
                    backgroundColor: scatterColors,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const point = ctx.raw;
                                return `${point.title}: ${point.y} interested`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: "category",
                        labels: scatterDateLabels,
                        title: { display: true, text: "Event date", color: "#8a9791", font: { size: 12 } },
                        grid: { color: "#eef2ef" }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        title: { display: true, text: "Students interested", color: "#8a9791", font: { size: 12 } },
                        grid: { color: "#eef2ef" }
                    }
                }
            }
        });
        markEmptyIfNoData(scatterCtx, document.getElementById("scatterChartEmpty"), scatterPoints.length > 0);

        const eventGrid = document.getElementById("eventGrid");

        function scrollEvents(direction) {
            const scrollAmount = 240;
            eventGrid.scrollBy({
                left: scrollAmount * direction,
                behavior: "smooth"
            });
        }

        const remarksModalOverlay = document.getElementById("remarksModalOverlay");
        const remarksModalTitle = document.getElementById("remarksModalTitle");
        const remarksModalStatus = document.getElementById("remarksModalStatus");
        const remarksModalText = document.getElementById("remarksModalText");
        const remarksModalClose = document.getElementById("remarksModalClose");

        const statusLabels = {
            APPROVED: { text: "Approved", className: "status-approved" },
            PENDING: { text: "Pending", className: "status-pending" },
            REJECTED: { text: "Rejected", className: "status-rejected" }
        };

        document.querySelectorAll(".view-remarks-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                const title = btn.dataset.title;
                const status = btn.dataset.status;
                const remarks = btn.dataset.remarks.trim();

                const statusInfo = statusLabels[status] || { text: status, className: "" };

                remarksModalTitle.textContent = title;
                remarksModalStatus.textContent = statusInfo.text;
                remarksModalStatus.className = "modal-status " + statusInfo.className;

                if (remarks === "") {
                    remarksModalText.textContent = "No Remarks Added";
                    remarksModalText.classList.add("no-remarks");
                } else {
                    remarksModalText.textContent = remarks;
                    remarksModalText.classList.remove("no-remarks");
                }

                remarksModalOverlay.classList.add("show");
            });
        });

        function closeRemarksModal() {
            remarksModalOverlay.classList.remove("show");
        }

        remarksModalClose.addEventListener("click", closeRemarksModal);

        remarksModalOverlay.addEventListener("click", (e) => {
            if (e.target === remarksModalOverlay) {
                closeRemarksModal();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                closeRemarksModal();
            }
        });
        // exposed globally: dashboard.view.php calls scrollEvents(...) via inline onclick=""
        if (typeof scrollEvents === "function") { window.scrollEvents = scrollEvents; }
    } catch (err) {
        console.error("[script.js] error in org/js/officer-dashboard.js:", err);
    }
})();

