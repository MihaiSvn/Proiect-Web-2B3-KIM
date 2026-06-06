document.addEventListener('DOMContentLoaded', function () {

    const monthYearText = document.getElementById('currentMonthYear');
    const calendarDays = document.getElementById('calendarDays');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');

    //data curenta
    let currentDate = new Date();

    // 'YYYY-MM-DD' : ['tip-antrenament1', 'tip-antrenament-2' etc]
    // const bookingsData = {
    //     '2026-05-05': ['physio'],
    //     '2026-05-08': ['strength'],
    //     '2026-05-10': ['fitness'],
    //     '2026-05-12': ['physio'],
    //     '2026-05-15': ['strength'],
    //     '2026-05-17': ['fitness'],
    //     '2026-05-26': ['physio'],
    //     '2026-05-29': ['strength']
    // };

    // DATASET IN CALENDAR WIDGET CU in data-sessions pentru a lua ce rezervari i
    const calendarContainer = document.getElementById('myCalendar');
    let bookingsData = {};
    if(calendarContainer.dataset.sessions){
        bookingsData = JSON.parse(calendarContainer.dataset.sessions);
    }
    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth(); // de la 0 la 11 ianuarie-decembrie

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        monthYearText.textContent = `${monthNames[month]} ${year}`; //Luna An din header May 2026

        //indexul primei zile din luna, duminica = 0, deci vom transforma sa fie luni = 0
        let firstDayIndex = new Date(year, month, 1).getDay();
        firstDayIndex = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

        //dai la luna urmatoare, iar date 0 nu exista ca luna incepe cu ziua 1, deci va da ultima zi din luna anterioara
        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();

        const today = new Date();
        const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;

        //resetez luna
        calendarDays.innerHTML = '';

        // 1: adaug spatiile goale pana la prima zi din luna
        for (let i = 0; i < firstDayIndex; i++) {
            const emptyDiv = document.createElement('div');
            calendarDays.append(emptyDiv);
        }

        // 2: adaug zilele efectiv
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('calendar__day');

            //pad(2,0) adauga 0 in fata daca val<10
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            //spanul care are nr zilei
            const dayNumberSpan = document.createElement('span');
            dayNumberSpan.textContent = String(day);
            dayDiv.appendChild(dayNumberSpan);

            //verific daca ziua din loop e ziua de azi
            if (isCurrentMonth && day === today.getDate()) {
                dayDiv.classList.add('active');
            }

            //verific daca am programari
            if (bookingsData[dateString]) {
                const dotRow = document.createElement('span');
                dotRow.style.display='flex';
                dotRow.style.flexDirection='row';
                dotRow.style.gap = '2px';
                dayDiv.appendChild(dotRow);
                bookingsData[dateString].forEach(type => {
                    const dotSpan = document.createElement('span');
                    dotSpan.classList.add('day__dot', `dot-${type}`);
                    dotRow.appendChild(dotSpan);
                });
            }
            calendarDays.appendChild(dayDiv);
        }
    }

    prevBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    renderCalendar(currentDate);
});