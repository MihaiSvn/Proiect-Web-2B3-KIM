<div class="custom-calendar">
    <!-- butoane de prev next si luna an -->

    <div class="calendar__header">
        <button class="calendar__nav-btn" id="prevMonth"><i class="fa-solid fa-chevron-left"></i></button>
        <h3 id="currentMonthYear">May 2026</h3>
        <button class="calendar__nav-btn" id="nextMonth"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    <!-- zilele sapt -->
    <div class="calendar__weekdays">
        <div>Mo</div>
        <div>Tu</div>
        <div>We</div>
        <div>Th</div>
        <div>Fr</div>
        <div>Sa</div>
        <div>Su</div>
    </div>

    <!-- grid pentru nr zile bagat de js -->
    <div class="calendar__days-grid" id="calendarDays">
        <div class="calendar__day">
            <span>26</span>
            <span class="day__dot dot-physio"></span>
        </div>
    </div>

    <!-- legenda -->
    <div class="calendar__legend">
        <span class="legend__item"><span class="day__dot dot-fitness"></span> Fitness</span>
        <span class="legend__item"><span class="day__dot dot-strength"></span> Strength</span>
        <span class="legend__item"><span class="day__dot dot-physio"></span> Physio</span>
    </div>
</div>