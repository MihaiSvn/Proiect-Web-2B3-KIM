
const openButtons = document.querySelectorAll('.js-open-popup');

const hiddenInputId = document.getElementById('popupId');

function closePopup(popupOverlay){
    popupOverlay.classList.add('popup__hidden');
}
function openPopup(event){

    const clickedButton = event.currentTarget;

    //luam id-ul stocat in buton
    const targetId = clickedButton.getAttribute('data-target'); //ex: popupOverlay_5

    const popupOverlay = document.getElementById(targetId);

    if(popupOverlay){
        popupOverlay.classList.remove('popup__hidden');

        // primul element gasit descedent cu clasa aia
        const closeBtn = popupOverlay.querySelector('.popup__closebutton');

        if(closeBtn){
            closeBtn.onclick = () => closePopup(popupOverlay);
        }

        popupOverlay.onclick = (e) =>{
            if(e.target === popupOverlay) closePopup(popupOverlay);
        }

    }
}

//adauc event lisntener pt fiecare buton de open
openButtons.forEach(button => {
    button.addEventListener('click', openPopup);
})
