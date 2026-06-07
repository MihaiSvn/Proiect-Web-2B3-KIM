
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
});

document.addEventListener('submit', function(e) {
    // verificam daca formularul declanseaza api
    if (e.target && e.target.classList.contains('js-api-form')) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        const apiUrl = form.getAttribute('action');

        //extrag datele
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        //fac un loading animation
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

        // fac request la api
        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const currentUrl = new URL(window.location.href);

                    currentUrl.searchParams.delete('error');
                    currentUrl.searchParams.set('success', result.message);

                    window.location.href = currentUrl.toString();
                } else {
                    const currentUrl = new URL(window.location.href);

                    currentUrl.searchParams.delete('success');
                    currentUrl.searchParams.set('error', result.message);

                    window.location.href = currentUrl.toString();
                }
            })
            .catch(error => {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('success');
                currentUrl.searchParams.set('error', 'Service unavailable');

                window.location.href = currentUrl.toString();
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
    }
});


