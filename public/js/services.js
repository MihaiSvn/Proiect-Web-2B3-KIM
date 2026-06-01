const serviceButtons = document.querySelectorAll('.js-service-tab');

function hideAllPanels(){

const servicePanels = document.querySelectorAll('.js-service-panel');

servicePanels.forEach(panel => {
    panel.classList.remove('services__panel--active');
});

}

function deactivateAllTabs(){

serviceButtons.forEach(button => {
    button.classList.remove('services__tab--active');
});

}

function changeService(event){

const clickedButton = event.currentTarget;

// luam id-ul panoului asociat butonului
const targetId = clickedButton.getAttribute('data-target');

const targetPanel = document.getElementById(targetId);

deactivateAllTabs();
hideAllPanels();

clickedButton.classList.add('services__tab--active');

if(targetPanel){
    targetPanel.classList.add('services__panel--active');
}

}

// adaugam event listener pentru fiecare tab
serviceButtons.forEach(button => {
    button.addEventListener('click', changeService);
});
