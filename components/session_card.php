<?php
/**
 * @var object $session //venit din profile.php, are toate informatiile despre un session, venit din foreach $plannedAndOngoingBookings
 */
?>

<?php

$start_time = strtotime($session->start_time);
$end_time = strtotime($session->end_time);

$booked_spots = $session->booked_spots;
$max_capacity = $session->max_capacity;

if ($booked_spots >= $max_capacity * 0.8) {
    $color = 'red';
} elseif ($booked_spots >= $max_capacity * 0.5) {
    $color = 'yellow';
} else {
    $color = 'lime';
}
?>

<div class="session__card theme-<?= htmlspecialchars($session->session_type) ?>">
    <div class="session__header">
        <p class="session__title"><?= htmlspecialchars($session->title) ?></p>
        <p class="session__category">
            <i class="fa-solid fa-dumbbell"></i> <?= htmlspecialchars( strtoupper($session->session_type)) ?>
        </p>
        <p class="session_status"><?= htmlspecialchars(strtoupper($session->status)) ?></p>
    </div>
    <!--date-->
    <div class="session__body">
        <div class="session_row">
            <i class="fa-regular fa-calendar"></i>
            <p class="session_text"><?= date('d M Y', $start_time) ?></p>
        </div>
        <!--ora de incepere si terminare-->
        <div class="session_row">
            <i class="fa-regular fa-clock"></i>
            <p class="session_text">
                <?= date('H:i', $start_time) ?>
                -
                <?= date('H:i', $end_time) ?>
            </p>
        </div>
        <!-- room -->
        <div class="session_row">
            <i class="fa-solid fa-location-dot"></i>
            <p class="session_text"><?= htmlspecialchars($session->room_name) ?></p>
        </div>
        <!-- Trainer -->
        <div class="session_row">
            <i class="fa-solid fa-chalkboard-user"></i>
            <p class="session_text"><?= htmlspecialchars('Trainer ' . $session->trainer_first_name . ' ' . $session->trainer_last_name) ?></p>
        </div>
        <!-- Capacity -->
        <div class="session_row">
            <i class="fa-solid fa-users"></i>
            <p class="session_text">
            <p style="color: <?= $color ?>">
                <?= htmlspecialchars($booked_spots) ?>
            </p>
            /
            <?= htmlspecialchars($max_capacity) ?>
            </p>
        </div>
    </div>
    <div class="session_footer">
        <button type="submit">Book now</button>
    </div>
</div>