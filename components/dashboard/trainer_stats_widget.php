<?php
/**
 * @var object $user //venit din TrainerDashboardController, are toate informatiile despre utilizatorul logat in sesiune
 * @var object $trainer //venit din TrainerDashboardController, are toate datele din tabela trainer despre user
 * @var array $plannedAndOngoingBookings //venit din TrainerDashboardController, are array de obiecte de tip Session
 * @var array $allBookings //venit din TrainerDashboardController, toate informatiile despre orice booking, mai putin cele canceled
 * @var array $unreadNotifications //venti din TrainerDashboardController, are toate informatiile despre notificari
 *
 */
?>

<?php

$upcomingSessionsCount = count($plannedAndOngoingBookings);
$upcomingClientsCount = 0;
$uniqueRooms = [];
$totalSecondsWorking = 0;

foreach ($plannedAndOngoingBookings as $session) {

    $upcomingClientsCount += (int)$session->booked_spots;

    $uniqueRooms[] = $session->room_name;

    $start = strtotime($session->start_time);
    $end = strtotime($session->end_time);
    $totalSecondsWorking += ($end - $start);
}

$assignedRoomsCount = count(array_unique($uniqueRooms));

$workingHoursCount = round($totalSecondsWorking / 3600);

?>


<div class="stats__container">


    <?php
    $cardIcon = 'fa-regular fa-calendar';
    $iconColor = '#D4A5A5';
    $iconBgColor = 'rgba(212, 165, 165, 0.15)';
    $statTitle = "UPCOMING SESSIONS";
    $statText = $upcomingSessionsCount;

    include 'components/stat_card.php';
    ?>

    <?php

    $cardIcon = 'fa-solid fa-user-group';
    $iconColor = '#B8A9C9';
    $iconBgColor = 'rgba(184, 169, 201, 0.15)';
    $statTitle = "UPCOMING CLIENTS";
    $statText = $upcomingClientsCount;
    include 'components/stat_card.php';
    ?>

    <?php
    $cardIcon = 'fa-solid fa-location-dot';
    $iconColor = '#A8C7BA';
    $iconBgColor = 'rgba(168, 199, 186, 0.15)';
    $statTitle = "ASSIGNED ROOMS";
    $statText = $assignedRoomsCount;
    include 'components/stat_card.php';
    ?>

    <?php
    $cardIcon = 'fa-regular fa-clock';
    $iconColor = '#F0B182';
    $iconBgColor = 'rgba(240, 177, 130, 0.15)';
    $statTitle = "WORKING HOURS";
    $statText = $workingHoursCount;
    include 'components/stat_card.php';
    ?>

</div>

