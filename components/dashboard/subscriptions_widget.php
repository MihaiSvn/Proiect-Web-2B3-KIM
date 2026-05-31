<?php
/**
 * @var array $activeSubscriptions //venit din DashboardController, are array de obiecte de tip UserSubscription
 */
?>

    <div class="dashboard__card-header">
        <h2 class="dashboard__card-title">Active memberships</h2>
    </div>
<?php if (empty($activeSubscriptions)): ?>
    No active subscriptions
<?php else: ?>
    <div class="subscription__grid">
        <?php foreach ($activeSubscriptions as $subscription): ?>

            <?php include 'components/profile_subscription_card.php'; ?>

            <?php
            $title = 'Suspend ' . htmlspecialchars($subscription->subscription_name);
            $submit = 'Confirm Suspend';
            $action = '/kim/subscription/suspend?id=' . $subscription->id;

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
    </div>
<?php endif; ?>