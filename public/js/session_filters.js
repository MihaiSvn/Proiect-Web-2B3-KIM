document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter__btn');
    const trainerSelect = document.getElementById('trainer-select');
    const trainersSelection = document.querySelectorAll('.trainer-select-option');
    let allButton;
    filterButtons.forEach((button)=>{
        if(button.getAttribute('data-filter')==='all'){
            allButton = button;
        }
    })

    function applyFilters(){
        let activeTypeFilter = 'all';

        filterButtons.forEach(btn => {
            if(btn.classList.contains('active')){
                activeTypeFilter = btn.getAttribute('data-filter');
            }
        });

        // all sau id
        const activeTrainerFilter = trainerSelect.value;

        // ma uit la toate session cards si daca n au data-type bun le scot

        //iau toate cele 7 coloane
        const scheduleColumn = document.querySelectorAll('.schedule__column');
        scheduleColumn.forEach((column)=>{
            //pt fiecare iau taote cardurile
            const sessionCards = column.querySelectorAll(".session__card");

            //numar cate sesiuni am
            let count = 0;

            sessionCards.forEach((card)=>{
                card.style.display = 'flex';

                const cardType = card.getAttribute('data-type');
                const cardTrainer = card.getAttribute('data-trainer');
                let shouldShow = true;


                if (activeTypeFilter !== 'all' && cardType !== activeTypeFilter) {
                    shouldShow = false;
                }

                if (activeTrainerFilter !== 'all' && cardTrainer !== activeTrainerFilter) {
                    shouldShow = false;
                }

                if (shouldShow) {
                    count++;
                } else {
                    card.style.display = 'none';
                }

            });

            trainersSelection.forEach(trainer => {
                trainer.style.display = 'flex';

                const specialization = trainer.getAttribute('data-type');
                let shouldShow = true;

                if(activeTypeFilter!=='all' && specialization!==activeTypeFilter){
                    shouldShow = false;
                }

                if(!shouldShow){
                    trainer.style.display = 'none';
                }

            })

            let countText = String(count) + " session";
            if(count!==1){
                countText = countText + "s";
            }

            const sessionCount = column.querySelector(".schedule__session-count");
            sessionCount.textContent = countText;

            //sa afisam No sessions
            const sessionScheduleText = column.querySelector(".schedule__column-text");
            if(count===0){
                sessionScheduleText.textContent = "No sessions";
                sessionScheduleText.style.display = '';
            } else {
                sessionScheduleText.textContent = "";
                sessionScheduleText.style.display = 'none';
            }
        });

    }

    //pt butoane
    function toggleFilter(filterButton){
        const type = filterButton.getAttribute('data-filter');
        // daca apas pe care deja e selectat si nu e butonul de all, fac all activ
        if(type!=='all' && filterButton.classList.contains('active')){
            filterButton.classList.remove('active');
            allButton.classList.add('active');
        } else if(filterButton===allButton && filterButton.classList.contains('active')){
        } else {
            filterButtons.forEach((button)=>{
                if(button.classList.contains('active')){
                    button.classList.remove('active');
                }
            });
            filterButton.classList.toggle('active');
        }

        // daca schimb tipul cat timp am vreun trainer selectat, ma mut inapoi la all trainers
        trainerSelect.value = "all";
        applyFilters();

    }

    filterButtons.forEach((button)=>{
        button.addEventListener('click', () => toggleFilter(button));
    });

    trainerSelect.addEventListener('change', () => {
        applyFilters();
    })
});