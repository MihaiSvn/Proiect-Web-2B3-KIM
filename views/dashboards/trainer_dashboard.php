<?php
/**
 * @var object $user //venit din TrainerDashboardController, are toate informatiile despre utilizatorul logat in sesiune
 * @var object $trainer //venit din TrainerDashboardController, are toate datele din tabela trainer despre user
 * @var array $plannedAndOngoingBookings //venit din TrainerDashboardController, are array de obiecte de tip Session
 * @var array $allBookings //venit din TrainerDashboardController, toate informatiile despre orice booking, mai putin cele canceled
 * @var array $unreadNotifications //venti din TrainerDashboardController, are toate informatiile despre notificari
 * @var array $sessionParticipantsMap map care asociaza id sesiune cu participantii lui
 *  [
 *   (int) $session_id => [
 *   0 => object(stdClass) {
 *   ->id,
 *   ->first_name,
 *   ->last_name,
 *   ->email,
 *   ->profile_picture,
 *   ->booked_at
 *   },
 *   1 => object(stdClass) { ... }
 *   ]
 *   ]
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.5">
    <link rel="stylesheet" href="/kim/public/css/subscription_card.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/session_card.css">
    <link rel="stylesheet" href="/kim/public/css/forms.css">
    <link rel="stylesheet" href="/kim/public/css/dashboard.css?v=1.2">
    <link rel="stylesheet" href="/kim/public/css/notifications.css">


    <link rel="stylesheet" href="/kim/public/css/calendar.css">

    <script src="/kim/public/js/popup.js" defer></script>
    <script src="/kim/public/js/sessions_carousel.js" defer></script>

    <script src="/kim/public/js/calendar.js" defer></script>
    <script src="/kim/public/js/header.js" defer></script>
    <script src="/kim/public/js/profile_button_listeners/notification_dismiss.js" defer></script>



    <!--    defer asteapta ca codul html sa se incarca ca apoi sa ruleze script ul-->
</head>
<body>

<?php include 'components/header.php'; ?>
<?php include 'components/alert.php'; ?>


<div class="trainer__dashboard__grid">



    <div class="dashboard__card grid-full">
        <?php include 'components/dashboard/greetings_text.php'; ?>
    </div>

    <div class="dashboard__card grid-full no-bg-card">

        <?php include 'components/dashboard/trainer_stats_widget.php'; ?>

    </div>


    <div class="dashboard__card grid-span-2">
        <?php include 'components/dashboard/notifications_widget.php'; ?>
    </div>

    <div class="dashboard__card grid-span-1">
        <?php include 'components/dashboard/calendar_widget.php'; ?>
    </div>

    <div class="dashboard__card grid-full">
        <?php include 'components/dashboard/sessions_widget.php'; ?>
    </div>


</div>

</body>
</html>
