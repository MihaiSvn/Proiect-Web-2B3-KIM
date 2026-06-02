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


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.5">
    <link rel="stylesheet" href="/kim/public/css/subscription_card.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/session_card.css">
    <link rel="stylesheet" href="/kim/public/css/forms.css">
    <link rel="stylesheet" href="/kim/public/css/dashboard.css?v=1.4">
    <link rel="stylesheet" href="/kim/public/css/notifications.css">


    <link rel="stylesheet" href="/kim/public/css/calendar.css">

    <script src="/kim/public/js/popup.js" defer></script>
    <script src="/kim/public/js/sessions_carousel.js" defer></script>

    <script src="/kim/public/js/calendar.js" defer></script>
    <script src="/kim/public/js/header.js"></script>
    <!--    defer asteapta ca codul html sa se incarca ca apoi sa ruleze script ul-->
</head>
<body>

<?php include 'components/headers/admin_header.php'; ?>
<?php include 'components/alert.php'; ?>

<div class="admin__dashboard__grid">

    <div class="dashboard__card admin-grid-full">
        <?php include 'components/dashboard/greetings_text.php'; ?>
    </div>

    <div class="dashboard__card admin-grid-full no-bg-card">
        <?php include 'components/dashboard/admin_stats_widget.php'; ?>
    </div>

    <div class="dashboard__card admin-grid-span-left">
        <?php include 'components/dashboard/admin_sessions_widget.php'; ?>
    </div>

    <div class="dashboard__card admin-grid-span-right">
        <?php include 'components/dashboard/notifications_widget.php'; ?>
    </div>

    <div class="dashboard__card admin-grid-span-right">
        <?php include 'components/dashboard/membership_admin_stat_widget.php'; ?>
    </div>

</div>

</body>
</html>