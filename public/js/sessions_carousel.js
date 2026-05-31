document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.session-slide');

    if(slides.length <=1 ) return;

    const prevBtn = document.getElementById('prevSession');
    const nextBtn = document.getElementById('nextSession');

    const counter = document.getElementById('sessionCounter');

    let currentIndex = 0;
    const totalSlides = slides.length;

    function updateSlider(){
        slides.forEach((slide,index)=>{
            if(index===currentIndex){
                //afisam slide ul curent si le ascundem pe restul
                slide.style.display = 'block';
            } else {
                slide.style.display = 'none';
            }
        });

        counter.textContent = `${currentIndex+1} / ${totalSlides}`;
    }

    nextBtn.addEventListener('click', () => {
        //daca suntem la ultimul mergem la primul
        currentIndex = (currentIndex === totalSlides - 1) ? 0 : currentIndex + 1;
        updateSlider();
    });

    prevBtn.addEventListener('click', () => {
        //daca suntem la primu mergem la ultima
        currentIndex = (currentIndex === 0) ? totalSlides - 1 : currentIndex - 1;
        updateSlider();
    });
})