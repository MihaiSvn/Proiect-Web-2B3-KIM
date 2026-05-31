<?php
/**
 * @var array $unreadNotifications //venti din DashBoardController, are toate informatiile despre notificari
 * @var object $user //venit din DashboardController, are toate informatiile despre utilizatorul logat in sesiune
 */
?>

<div class="dashboard__card-header">
    <h2 class="dashboard__card-title">Recent Notifications</h2>
    <span class="notifications__badge"><?= count($unreadNotifications) ?> New</span>

    <?php if (count($unreadNotifications) > 0): ?>
        <form action="/kim/notifications/mark-all-read" method="POST" class="notifications__form-all">
            <input type="hidden" name="notification_id" value="<?= $user->id ?>">
            <button type="submit" class="notifications__btn-read-all">
                <i class="fa-solid fa-check-double"></i> Mark all as read
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="notifications__list">

    <?php foreach ($unreadNotifications as $notification): ?>
        <?php include 'components/notification_item.php'; ?>
    <?php endforeach; ?>

</div>