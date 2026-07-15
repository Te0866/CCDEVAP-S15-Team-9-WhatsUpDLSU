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
    if (eventId) {

        renderSidebar(eventsData);

        const event = eventsData.find(e => Number(e.id) === eventId);

        if (event) {
            selectedEvent = event;
            showEventDetail(event);

            document.querySelectorAll(".event-item").forEach(btn => {
    btn.classList.toggle(
        "active",
        Number(btn.dataset.id) === event.id
    );
});
        } else {
            clearFilters();
        }

    } else {
        clearFilters();
    }

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

    document.getElementById("interestedBtn").style.display = "inline-block";
    updateInterestedButton(event.isInterested);
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

    fetch(`get-comments.php?event_id=${event.id}`)
        .then(res => res.json())
        .then(comments => renderCommentsCarousel(comments))
        .catch(err => {
            console.error('Failed to load comments:', err);
            renderCommentsCarousel([]);
        });
}

let currentImageIndex = 0;
let currentCommentIndex = 0;
let commentsIntervalId = null;

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
}

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
    btn.textContent = isInterested ? "Interested ✓" : "Interested!";
    btn.classList.toggle("interested-active", isInterested);
}


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
            addCommentLocally({ author: result.author, text: result.text });
        } else {
            showAlert("Error", result.error || "Failed to post comment.");
        }

    } catch (err) {
        console.error('Failed to submit comment:', err);
        showAlert("Error", "Something went wrong posting your comment.");
    }

    closeCommentModal();
});

function addCommentLocally(comment) {
    if (!selectedEvent.comments) selectedEvent.comments = [];
    selectedEvent.comments.push(comment);
    renderCommentsCarousel(selectedEvent.comments);
}

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

    if (category !== "All") {
        filtered = filtered.filter(event =>
            event.category.toLowerCase() === category.toLowerCase()
        );
    }
     if (status !== "All") {
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
    categoryParam ? categoryParam : "All";
    document.getElementById("sortFilter").value = "Oldest";
    document.getElementById("statusFilter").value = "All";

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
