<?php
// CELE DE USER VIN DIN PROFILE.PHP, IAR CELELALTE DIN ProfileNotificationsController
/**
 * @var string $userId
 * @var string $userFirstName
 * @var string $userLastName
 * @var string $userJoinDate
 * @var string $userRole
 * @var string $userEmail
 * @var array $groupedSubs // all subscriptions by user id, venit din ProfileMemberShipHistoryControlelr
 * are structura
 *
 * $grouped = [
 * 'active' => [],
 * 'suspended' => [],
 * 'expired' => []
 * ];
 *
 * unde fiecare tine un obiect de subscription
 */

?>

<div class="profile__list">


    <?php if (count($groupedSubs['active']) > 0): ?>
        <h3>Active Memberships</h3>
        <?php foreach ($groupedSubs['active'] as $subscription): ?>
            <?php include 'components/profile_subscription_card.php'; ?>
        <?php endforeach; ?>

    <?php endif; ?>


    <?php if (count($groupedSubs['suspended']) > 0): ?>

        <h3>Suspended Memberships</h3>
        <?php foreach ($groupedSubs['suspended'] as $subscription): ?>
            <?php include 'components/profile_subscription_card.php'; ?>
        <?php endforeach; ?>

    <?php endif; ?>

    <?php if (count($groupedSubs['expired']) > 0): ?>
        <h3>Expired Memberships</h3>
        <?php foreach ($groupedSubs['expired'] as $subscription): ?>
            <?php include 'components/profile_subscription_card.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
