<?php
/**
 * @var array $activeSubscriptions //venit din MemberDashboardController, are array de obiecte de tip UserSubscription
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



        <?php endforeach; ?>
    </div>
<?php endif; ?>