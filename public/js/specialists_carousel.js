document.addEventListener('DOMContentLoaded', () => {

    const expertCards =
        document.querySelectorAll('.expert-card');

    const prevBtn =
        document.getElementById('expertsPrev');

    const nextBtn =
        document.getElementById('expertsNext');

    if(
        expertCards.length <= 4 ||
        !prevBtn ||
        !nextBtn
    ){
        return;
    }

    let currentIndex = 0;

    function updateCarousel(){

        expertCards.forEach((card,index) => {

            if(
                index >= currentIndex &&
                index < currentIndex + 4
            ){

                card.style.display = '';

            }else{

                card.style.display = 'none';

            }

        });

    }

    function showNextExperts(){

        if(currentIndex < expertCards.length - 4){

            currentIndex++;

            updateCarousel();

        }

    }

    function showPreviousExperts(){

        if(currentIndex > 0){

            currentIndex--;

            updateCarousel();

        }

    }

    nextBtn.addEventListener('click', showNextExperts);

    prevBtn.addEventListener('click', showPreviousExperts);

    updateCarousel();

});