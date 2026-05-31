<?php
/**
 * @var object $notification //venit din DashboardController, obiect cu notification
 * @var object $user //venit din DashboardController, are toate informatiile despre utilizatorul logat in sesiune
 */
?>

<div class="notification__item <?= $notification->is_read ? 'notification__read' : '' ?>">
    <div class="notification__icon">🔔&#xFE0E;</div>

    <div class="notification__content">
        <h4 class="notification__subject"><?= $notification->title ?></h4>
        <p class="notification__details"><?= $notification->message?></p>
    </div>

    <div class="notification__actions">
        <span class="notification__time">
            <?= $this->notificationService->getTimeAgo($notification->created_at) ?>
        </span>

        <?php if (!$notification->is_read) : ?>
        <form action="/kim/notifications/mark-read" method="POST" class="notification__form-single">
            <input type="hidden" name="notification_id" value="<?= $notification->id ?>">

            <button type="submit" class="notification__btn-read" title="Mark as read">
                <i class="fa-solid fa-check"></i>
            </button>
        </form>
        <?php endif ?>
    </div>
</div>