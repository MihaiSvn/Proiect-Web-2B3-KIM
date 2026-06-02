<?php
/**
 * @var array $subscriptionTypesStats // venit din AdminDashboardController, e array de forma
 */
?>


<div class="dashboard__card-header">
    <div class="dashboard__card-title">Membership Snapshot</div>
</div>

<div class="admin__subscription-stats-container">

<?php foreach($subscriptionTypesStats as $subscription): ?>

<?php
    $subscriptionType = $subscription->subscription_type;
    $totalActive = $subscription->total_active;

    ?>
<?php include 'components/membership_admin_stat_card.php'; ?>

<?php endforeach; ?>
</div>
