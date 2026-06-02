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


    <div class="session__admin-container">

        <?php foreach ($plannedAndOngoingBookings as $session): ?>

                <?php include "components/session_card.php"; ?>

        <?php endforeach; ?>
    </div>
<?php endif; ?>