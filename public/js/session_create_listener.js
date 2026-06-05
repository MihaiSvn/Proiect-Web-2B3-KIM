document.addEventListener('DOMContentLoaded', function (){
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    if(startTimeInput && endTimeInput){
        startTimeInput.addEventListener('change', function (){
            endTimeInput.min = this.value;

            //resetam ca sa l punem sa aleaga din nou
            if (endTimeInput.value && endTimeInput.value < this.value) {
                endTimeInput.value = '';
            }
        });
    }


    const roomSelect = document.getElementById('session_room');
    const roomJsonDiv = document.getElementById('hidden_room_data');
    if(roomSelect && roomJsonDiv){
        //cream element de info cu echipamente
        const equipmentInfoBox = document.createElement('p');
        equipmentInfoBox.className = 'form__question';
        equipmentInfoBox.style.marginBottom = '15px';
        equipmentInfoBox.style.textAlign = 'left';

        const rawPayload = roomJsonDiv.getAttribute('data-payload');
        const roomDetails = JSON.parse(rawPayload);

        //inserez exact dupa select
        roomSelect.parentNode.insertBefore(equipmentInfoBox,roomSelect.nextSibling);


        roomSelect.addEventListener('change', function (){
            const selectedRoomId = this.value;
            if(roomDetails[selectedRoomId]){
                const eqText = roomDetails[selectedRoomId]['equipment'];
                equipmentInfoBox.innerHTML = `<i class="fa-solid fa-dumbbell" style="color: var(--kim-dark-pink);"></i> <b>Equipment:</b> ${eqText}`;
            } else {
                equipmentInfoBox.innerHTML = '';
            }

        });
        roomSelect.dispatchEvent(new Event('change'));
    }

    //pt admin
    const sessionTrainerSelect = document.getElementById('session_trainer');
    const trainerJsonDiv = document.getElementById('hidden_trainer_data');

    if(sessionTrainerSelect && trainerJsonDiv && roomJsonDiv && roomSelect){
        const trainerData = JSON.parse(trainerJsonDiv.getAttribute('data-payload'));
        const roomData = JSON.parse(roomJsonDiv.getAttribute('data-payload'));

        //salvez optiunile initale pt a le putea restuara
        const originalRoomOptions = Array.from(roomSelect.options);

        sessionTrainerSelect.addEventListener('change', function () {
            const selectedTrainerId = this.value;

            if(trainerData[selectedTrainerId]){
                const trainerSpecialization = trainerData[selectedTrainerId]['specialization'];

                //curatam select ul si punem optiunea placeholder prima
                roomSelect.innerHTML = '';
                roomSelect.appendChild(originalRoomOptions[0]);

                originalRoomOptions.forEach(option => {
                    const roomId = option.value;
                    //pt fiecare camera din asta orginal verific daca id ul lui exista in map si daca are tip = specializare
                    if(roomId && roomData[roomId] && roomData[roomId].type === trainerSpecialization){
                        roomSelect.appendChild(option); // cloneNode e important pt a nu muta optiunea ci a o copia
                    }
                });


                roomSelect.dispatchEvent(new Event('change'));

            }
        });

        sessionTrainerSelect.dispatchEvent(new Event('change'));
    }


});