<?php
/**
 * @var array $plannedAndOngoingBookings //venit din ProfileControoler, are array de obiecte de tip Session
 */
?>


    <div class="dashboard__card-header">
        <h2 class="dashboard__card-title">Upcoming sessions</h2>
    </div>
<?php if (empty($plannedAndOngoingBookings)): ?>
    No upcoming sessions
<?php else: ?>

    <?php
    $totalSessions = count($plannedAndOngoingBookings);
    if ($totalSessions > 1):
        ?>
        <div class="carousel__controls">
            <button class="carousel__btn" id="prevSession"><i class="fa-solid fa-chevron-left"></i></button>
            <span class="carousel__counter" id="sessionCounter">1 / <?= $totalSessions ?></span>
            <button class="carousel__btn" id="nextSession"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    <?php endif; ?>

    <div class="session__carousel-container">

        <?php foreach ($plannedAndOngoingBookings as $index => $session): ?>

            <div class="session-slide" style="<?= $index === 0 ? 'display: block;' : 'display: none;' ?>">
                <?php include "components/session_card.php"; ?>
            </div>

        <?php endforeach; ?>
    </div>
<?php endif; ?>