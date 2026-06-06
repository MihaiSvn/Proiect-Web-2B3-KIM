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


<div class="profile__subscriptions_list">

    <?php if (count($groupedSubs['active']) > 0): ?>
        <h3>Active Memberships</h3>
        <?php foreach ($groupedSubs['active'] as $subscription): ?>
            <?php include 'components/profile_subscription_card.php'; ?>
            <?php
            $title = 'Suspend ' . htmlspecialchars($subscription->subscription_name);
            $submit = 'Confirm Suspend';
            $action = '/kim/subscription/suspend?id=' . $subscription->id;
            $infoText = "Note: You can't unfreeze a membership. You will need to wait for the suspension period.";

            require_once __DIR__ . '/../../classes/FormField.php';

            $daysField = FormField::create('Days to suspend', 'suspend_days')
                    ->type('number')
                    ->required()
                    ->placeholder('1')
                    ->limits(1, $subscription->suspending_days_left);
            $formBody = [
                    $daysField
            ];

            $popupId = 'popupOverlay_' . $subscription->id; //pt a o putea gasi in js

            include 'components/popup.php';
            ?>
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
