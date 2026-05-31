<?php
/**
 * @var object $session
 */
$start_time = strtotime($session->start_time);
$end_time = strtotime($session->end_time);

$icons = [
        'fitness' => 'fa-solid fa-dumbbell',
        'physiotherapy' => 'fa-solid fa-heart-pulse',
        'strength' => 'fa-solid fa-weight-hanging'
];

$statusIcons = [
        'planned' => 'fa-regular fa-clock',
        'ongoing' => 'fa-solid fa-bolt',
        'canceled' => 'fa-regular fa-circle-xmark',
        'completed' => 'fa-regular fa-circle-check'
];

$status = isset($session->status) ? $session->status : 'planned';

$currentStatusIcon = isset($statusIcons[$status]) ? $statusIcons[$status] : 'fa-solid fa-circle-info';
$iconClass = isset($icons[$session->session_type]) ? $icons[$session->session_type] : 'fa-solid fa-dumbbell';
$userRole = $_SESSION['user_role'];

//$start_time = time();
//$status = 'canceled';
?>

<div class="session__card">

    <!--    top are iconita si status-->
    <div class="session__top">
        <div class="session__icon-wrapper <?= "theme-" . $session->session_type ?>">
            <i class="<?= $iconClass ?>"></i>
        </div>

        <div class="session__status-badge status-<?= $status ?>">
            <i class="<?= $currentStatusIcon ?>"></i> <?= htmlspecialchars(ucfirst($status)) ?>
        </div>
    </div>

    <!--    titlu, data ora spatiu-->
    <div class="session__main">
        <h3 class="session__title"><?= htmlspecialchars($session->title) ?></h3>

        <div class="session__details-list">
            <div class="session__detail-item">
                <i class="fa-regular fa-calendar"></i>
                <span><?= date('d M Y', $start_time) ?></span>
            </div>

            <div class="session__detail-item">
                <i class="fa-regular fa-clock"></i>
                <span><?= date('H:i', $start_time) ?> - <?= date('H:i', $end_time) ?></span>
            </div>

            <div class="session__detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <span><?= htmlspecialchars($session->room_name) ?></span>
            </div>
        </div>
    </div>

    <div class="session__divider"></div>

    <!--    cati partiicpanti, trainer ul daca utilziatorul e membru, buton de cancel-->
    <div class="session__bottom">

        <?php if ($userRole === 'member'): ?>
            <span class="person__label">Trainer</span>
            <p class="person__name"><?= htmlspecialchars($session->trainer_first_name . ' ' . $session->trainer_last_name) ?></p>
        <?php endif ?>
        <div class="session__capacity">
            <div class="capacity__count">
                <i class="fa-solid fa-user-group"></i>
                <span class="count__numbers"><?= htmlspecialchars($session->booked_spots) ?>/<?= htmlspecialchars($session->max_capacity) ?></span>
            </div>
            <span class="capacity__label">participants</span>
        </div>

        <?php if ($userRole === 'trainer' || $userRole === 'admin'): ?>
            <div class="session__buttons-row">
                <!--                <form action="/kim/sessions/participants" method="POST" class="session__form-flex">-->
                <!--                    <input type="hidden" name="session_id" value="-->
                <?php //= $session->session_id ?><!--">-->
                <!--                    <button type="submit" class="session__btn">-->
                <!--                        View Participants-->
                <!--                    </button>-->
                <!--                </form>-->

                <?php if ($status === 'planned'): ?>
                    <form action="/kim/sessions/cancel-session" method="POST" class="session__form-flex">
                        <input type="hidden" name="session_id" value="<?= $session->session_id ?>">
                        <button type="submit" class="session__btn session__btn--danger">
                            Cancel Class
                        </button>
                    </form>
                <?php endif; ?>
            </div>

        <?php else: ?>

<!--        APARE BUTONUL DOAR DACA STATUSUL E PLANNED SAU ONGOING-->
            <?php if ($status === 'planned' || $status === 'ongoing'): ?>
                <form action="/kim/sessions/cancel-booking" method="POST" class="session__form">
                    <input type="hidden" name="session_id" value="<?= $session->session_id ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
<!--                    nu mai poti da cancel daca e planned dar sunt mai putin de 24 de ore de la training, sau daca e ongoing-->
<!--                    cred ca e suficinet si doar aia de 24 de ore ca teoretic e implicit daca e ongoing-->
                    <button type="submit" class="session__btn session__btn--danger"
                            <?php if (!($status === 'planned' && $start_time > strtotime('+24 hours'))): ?>
                                disabled
                                title="Can't cancel this booking anymore"
                            <?php endif; ?>
                    >
                        Cancel Booking
                    </button>
                </form>

            <?php endif; ?>

        <?php endif; ?>
    </div>

</div>