<?php
/**
 * @var object $user //venit din AdminDashboardController, are toate informatiile despre utilizatorul logat in sesiune
 * @var numeric $trainersCount // venit din AdminDashboardController, numarul tuturor traineirlor
 * @var array $usersStats // venit din AdminDashboardController, numarul tuturor userilor e de forma
 * [
 * 'displayValue' => $displayValue,  ->  numarul efectiv
 * 'trend' => $trend   ->  pt stat card
 * ];
 *
 * @var array $plannedAndOngoingBookings // venit din AdminDashboardController, are array de obiecte de tip Session
 * @var array $monthlyRevenueStats // venit din AdminDashboardController, la fel ca la usersStats
 * @var array $subscriptionTypesStats // venit din AdminDashboardController, e array de forma
 * Array
 * (
 * [0] => stdClass Object
 * (
 * [subscription_type] => fitness
 * [total_active] => 380
 * )
 *
 * [1] => stdClass Object
 * (
 * [subscription_type] => strength
 * [total_active] => 295
 * )
 *
 * [2] => stdClass Object
 * (
 * [subscription_type] => physiotherapy
 * [total_active] => 200
 * )
 *
 * [3] => stdClass Object
 * (
 * [subscription_type] => all
 * [total_active] => 142
 * )
 * )
 * pt fiecare tip
 *
 * @var array $unreadNotifications //venti din AdminDashboardController, are toate informatiile despre notificari
 *
 */
?>

<div class="stats__container">


    <?php
    $cardIcon = 'fa-regular fa-users';
    $iconColor = '#D4A5A5';
    $iconBgColor = 'rgba(212, 165, 165, 0.15)';
    $statTitle = "Active Members";
    $statText = $usersStats['displayValue'];;
    $trend = $usersStats['trend'];

    include 'components/stat_card.php';
    ?>

    <?php

    $cardIcon = 'fa-regular fa-calendar';
    $iconColor = '#B8A9C9';
    $iconBgColor = 'rgba(184, 169, 201, 0.15)';
    $statTitle = "Upcoming Sessions";
    $statText = count($plannedAndOngoingBookings);
    $trend = null;
    include 'components/stat_card.php';
    ?>

    <?php
    $cardIcon = 'fa-solid fa-user-check';
    $iconColor = '#A8C7BA';
    $iconBgColor = 'rgba(168, 199, 186, 0.15)';
    $statTitle = "Available Specialists";
    $statText = $trainersCount;
    $trend = null;

    include 'components/stat_card.php';
    ?>

    <?php
    $cardIcon = 'fa-solid fa-dollar-sign';
    $iconColor = '#8CA8C9';
    $iconBgColor = 'rgba(140, 168, 201, 0.15)';
    $statTitle = "Monthly Revenue";
    $statText = $monthlyRevenueStats['displayValue'];
    $trend = $monthlyRevenueStats['trend'];
    include 'components/stat_card.php';
    ?>

</div>

