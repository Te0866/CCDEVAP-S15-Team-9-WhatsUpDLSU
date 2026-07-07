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

fetch('events.json').then(res => res.json()).then(data => {
        eventsData = data;

        const params = new URLSearchParams(window.location.search);
        const categoryParam = params.get('category');

        if (categoryParam) {
            const categorySelect = document.querySelectorAll('.filter-box')[1]; 
            categorySelect.value = categoryParam;

            const filtered = eventsData.filter(e => e.category === categoryParam);
            renderSidebar(filtered);
            if (filtered.length > 0) {
                selectedEvent = filtered[0];
                showEventDetail(selectedEvent);
            }
        } else {
            renderSidebar(eventsData);
            if (eventsData.length > 0) {
                selectedEvent = eventsData[0];
                showEventDetail(selectedEvent);
            }
        }
    });

function renderSidebar(events) {
    const sidebar = document.getElementById('eventSidebar');
    sidebar.innerHTML = '';

    events.forEach((event, index) => {
        const btn = document.createElement('button');
        btn.className = 'event-item';
        if (index === 0) btn.classList.add('active');
        btn.textContent = event.title;

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

/* TEST CAROUSEL */

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
        }, 5000); // change interval (ms) as you like
    }
}

function updateCommentsTrack() {
    const track = document.getElementById('commentsTrack');
    track.style.transform = `translateX(-${currentCommentIndex * 100}%)`;
    document.querySelectorAll('#commentsDots .dot').forEach((dot, i) => {
        dot.classList.toggle('active', i === currentCommentIndex);
    });
}

/* TEST CAROUSEL */

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
