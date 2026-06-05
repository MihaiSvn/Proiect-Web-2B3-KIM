<?php
// CELE DE USER VIN DIN PROFILE.PHP, IAR CELELALTE DIN ProfileNotificationsController
/**
 * @var string $userId
 * @var string $userFirstName
 * @var string $userLastName
 * @var string $userJoinDate
 * @var string $userRole
 * @var string $userEmail
 * @var array $allNotifications // lista cu toate notificarile
 * @var array $unreadNotifications //lista cu notificarile unread
 */
?>

<?php if (count($unreadNotifications) > 0): ?>
    <form action="/kim/notifications/mark-all-read" method="POST" class="notifications__form-all">
        <input type="hidden" name="notification_id" value="<?= $userId ?>">
        <button type="submit" class="notifications__btn-read-all">
            <i class="fa-solid fa-check-double"></i> Mark all as read
        </button>
    </form>
<?php endif; ?>
<br>
<div class="notifications__list">

<?php foreach ($allNotifications as $notification): ?>
    <?php include 'components/notification_item.php'; ?>
<?php endforeach; ?>
</div>