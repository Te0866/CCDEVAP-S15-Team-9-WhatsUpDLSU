const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");
profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("show");
});

document.addEventListener("click", () => {
    dropdownMenu.classList.remove("show");
});


let eventsData = [];
let selectedEvent = null;

fetch('get-events.php')
    .then(res => res.json())
    .then(data => {

        eventsData = data;

        const params = new URLSearchParams(window.location.search);
        const categoryParam = params.get('category');

        let eventsToDisplay = eventsData;

        if (categoryParam) {
            const categorySelect = document.querySelectorAll('.filter-box')[1];
            categorySelect.value = categoryParam;

            eventsToDisplay = eventsData.filter(event =>
                event.category.toUpperCase() === categoryParam.toUpperCase()
            );
        }

        renderSidebar(eventsToDisplay);

        if (eventsToDisplay.length > 0) {
            selectedEvent = eventsToDisplay[0];
            showEventDetail(selectedEvent);
        } else {
            showNoEvent();
        }
    })
    .catch(error => {
        console.error("Error loading events:", error);
        showNoEvent();
    });

function renderSidebar(events) {
    const sidebar = document.getElementById('eventSidebar');
    sidebar.innerHTML = '';

    if (events.length === 0) {
        sidebar.innerHTML = `
            <div class="no-events">
                No Events Available
            </div>
        `;
        return;
    }

    events.forEach((event, index) => {
        const btn = document.createElement('button');
        btn.className = 'event-item';

        if (index === 0) {
            btn.classList.add('active');
        }

        btn.textContent = event.title;
        btn.dataset.id = event.id;

        btn.addEventListener('click', () => {
            document.querySelectorAll('.event-item').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedEvent = event;
            showEventDetail(event);
        });

        sidebar.appendChild(btn);
    });
}

function showEventDetail(event) {
    document.getElementById("interestedBtn").style.display = "inline-block";
    document.getElementById("postCommentBtn").style.display = "inline-block";
    document.getElementById('eventTitle').textContent = event.title;
    document.getElementById('category').textContent = event.category;
    document.getElementById('duration').textContent =
        formatTime(event.startTime) + ' - ' + formatTime(event.endTime);
    document.getElementById('venue').textContent = event.venue;
    document.getElementById('status').textContent = event.status;
    document.getElementById('registration').textContent = event.registration;
    document.getElementById('organizer').textContent = event.organizer;
    document.getElementById('description').textContent = event.description;

    renderImageCarousel(event.images);
    renderCommentsCarousel(event.comments);
}

/* search autocomplete stuff is down here in case I can't find it */

const searchInput = document.getElementById('searchInput');
const searchSuggestions = document.getElementById('searchSuggestions');
let highlightedIndex = -1;

searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim().toLowerCase();
    highlightedIndex = -1;

    if (!query) {
        searchSuggestions.classList.remove('show');
        searchSuggestions.innerHTML = '';
        return;
    }

    const matches = eventsData
        .filter(event => event.title.toLowerCase().includes(query))
        .slice(0, 6);

    if (matches.length === 0) {
        searchSuggestions.classList.remove('show');
        searchSuggestions.innerHTML = '';
        return;
    }

    searchSuggestions.innerHTML = matches.map(event => {
        const highlighted = highlightMatch(event.title, query);
        return `<div class="suggestion-item" data-id="${event.id}">${highlighted}</div>`;
    }).join('');

    searchSuggestions.classList.add('show');

    document.querySelectorAll('.suggestion-item').forEach(item => {
        item.addEventListener('click', () => {
            selectEventById(item.dataset.id);
        });
    });
});

function highlightMatch(title, query) {
    const index = title.toLowerCase().indexOf(query);
    if (index === -1) return title;
    const before = title.slice(0, index);
    const match = title.slice(index, index + query.length);
    const after = title.slice(index + query.length);
    return `${before}<strong>${match}</strong>${after}`;
}

function selectEventById(id) {
    const event = eventsData.find(e => String(e.id) === String(id));
    if (!event) return;

    searchInput.value = event.title;
    searchSuggestions.classList.remove('show');
    searchSuggestions.innerHTML = '';

    document.querySelectorAll('.event-item').forEach(b => b.classList.remove('active'));
    const matchingBtn = document.querySelector(`.event-item[data-id="${event.id}"]`);
    if (matchingBtn) matchingBtn.classList.add('active');

    selectedEvent = event;
    showEventDetail(event);
}

searchInput.addEventListener('keydown', (e) => {
    const items = document.querySelectorAll('.suggestion-item');
    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        highlightedIndex = (highlightedIndex + 1) % items.length;
        updateHighlight(items);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        highlightedIndex = (highlightedIndex - 1 + items.length) % items.length;
        updateHighlight(items);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (highlightedIndex >= 0 && items[highlightedIndex]) {
            selectEventById(items[highlightedIndex].dataset.id);
        }
    } else if (e.key === 'Escape') {
        searchSuggestions.classList.remove('show');
    }
});

function updateHighlight(items) {
    items.forEach((item, i) => {
        item.classList.toggle('highlighted', i === highlightedIndex);
    });
}

document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
        searchSuggestions.classList.remove('show');
    }
});

/* search autocomplete stuff is up here in case I can't find it */

/* carousel stuff is down here in case I can't find it */

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

function formatTime(time24) {
    const [hour, minute] = time24.split(':');
    const h = parseInt(hour);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const displayHour = h % 12 === 0 ? 12 : h % 12;
    return `${displayHour}:${minute} ${ampm}`;
}

document.getElementById("interestedBtn").addEventListener("click", () => {
    const interestedEvents = JSON.parse(localStorage.getItem("interestedEvents")) || [];
    const eventData = { title: selectedEvent.title };

    const exists = interestedEvents.some(event => event.title === eventData.title);
    if (!exists) {
        interestedEvents.push(eventData);
        localStorage.setItem("interestedEvents", JSON.stringify(interestedEvents));
        alert("Added to Interested Events!");
    } else {
        alert("Already added!");
    }
});

/* post comment stuff is down here in case I can't find it */

const commentModalOverlay = document.getElementById('commentModalOverlay');
const commentForm = document.getElementById('commentForm');
const postCommentBtn = document.getElementById('postCommentBtn');
const cancelCommentBtn = document.getElementById('cancelCommentBtn');
const commenterNameEl = document.getElementById('commenterName');
const anonToggle = document.getElementById('anonToggle');

function getLoggedInUsername() {
    // TODO: once PHP sessions/login are in place, replace this with
    // whatever mechanism actually identifies the logged-in student
    // (e.g. a value rendered server-side into the page, or a session
    // check endpoint) rather than localStorage.
    const stored = localStorage.getItem('loggedInUser');
    return stored || 'Student Name';
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
        // Keep the real username server-side even when posted anonymously,
        // so moderators/admins can still trace it back if needed. The
        // PHP endpoint should store this separately and never expose it
        // in public-facing comment reads when is_anonymous is true.
        posted_by: username,
        is_anonymous: isAnonymous,
        text: document.getElementById('commentMessage').value.trim(),
        created_at: new Date().toISOString()
    };

    if (!newComment.text) return;

    try {
        const response = await fetch('submit_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newComment)
        });

        if (!response.ok) throw new Error('Server responded with an error');

        const savedComment = await response.json();
        addCommentLocally(savedComment);

    } catch (err) {
        console.warn('submit_comment.php not available yet, adding comment locally:', err);
        addCommentLocally({ author: newComment.author, text: newComment.text });
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
/* post comment stuff is up here in case I can't find it */
