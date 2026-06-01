<?php
/**
 * @var array $allBookings //venit din danshboardController, toate informatiile despre orice booking, mai putin cele canceled
 * de mentionat ca se numeste asa, dar nu trb booking, trebuie sa aiba din sesiune date,type (in caz de il vreau pt traineri)
 */
?>

<?php

$calendarData = [];

if(!empty($allBookings)){
    foreach($allBookings as $session){
        $date = date("Y-m-d", strtotime($session->start_time));

        //daca nu mai exista data asta
        if(!isset($calendarData[$date])){
            $calendarData[$date] = [];
        }

        //ca sa nu duplic tipul anternamentului
        if(!in_array($session->session_type, $calendarData[$date])){
            $calendarData[$date][] = $session->session_type;
        }
    }
}
$calendarJson = json_encode($calendarData);
?>
<div class="dashboard__card-header">
    <div class="dashboard__card-title"
    id="myCalendar" data-sessions="<?= htmlspecialchars($calendarJson, ENT_QUOTES, 'UTF-8'); ?>"
    >Calendar</div>
</div>

<?php include 'components/calendar.php'; ?>