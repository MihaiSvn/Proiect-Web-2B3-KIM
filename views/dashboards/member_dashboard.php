<?php
/**
 * @var object $user //venit din DashboardController, are toate informatiile despre utilizatorul logat in sesiune
 * @var array $activeSubscriptions //venit din DashboardController, are array de obiecte de tip UserSubscription
 * @var array $plannedAndOngoingBookings //venit din ProfileControoler, are array de obiecte de tip Session
 * @var array $unreadNotifications //venti din DashBoardController, are toate informatiile despre notificari
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
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.4">
    <link rel="stylesheet" href="/kim/public/css/subscription_card.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/session_card.css">
    <link rel="stylesheet" href="/kim/public/css/forms.css">
    <link rel="stylesheet" href="/kim/public/css/dashboard.css?v=1.2">

    <script src="/kim/public/js/popup.js" defer></script>
    <script src="/kim/public/js/sessions_carousel.js" defer></script>
    <!--    defer asteapta ca codul html sa se incarca ca apoi sa ruleze script ul-->
</head>
<body>

<?php include 'components/headers/member_header.php'; ?>
<?php include 'components/alert.php'; ?>


<div class="member__dashboard__grid">

    <div class="dashboard__card grid-full">
        <?php include 'components/dashboard/greetings_text.php'; ?>
    </div>

    <div class="dashboard__card grid-full">
        <?php include 'components/dashboard/notifications_widget.php'; ?>
    </div>

    <div class="dashboard__card grid-span-2">
        <?php include 'components/dashboard/sessions_widget.php'; ?>
    </div>

    <div class="dashboard__card grid-span-1 calendar-placeholder">
        <i class="fa-regular fa-calendar-days" style="font-size: 2rem; color: #d4a5a5; margin-bottom: 10px;"></i>
        <h3 style="color: #31231E;">May 2026</h3>
        <p style="color: #8c8585; font-size: 0.9rem;">Calendar integration coming soon...</p>
    </div>

    <div class="dashboard__card grid-full">
        <?php include 'components/dashboard/subscriptions_widget.php'; ?>
    </div>

</div>

</body>
</html>