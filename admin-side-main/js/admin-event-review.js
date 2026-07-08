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


document.getElementById("approveBtn").addEventListener("click",()=>{
    alert("Event Approved Successfully");
});


document.getElementById("rejectBtn").addEventListener("click",()=>{
    alert("Event Rejected");
});


/* Carousel */

const eventImages = [
    "img/animusika1.jpg",
    "img/animusika2.jpg"
];

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
