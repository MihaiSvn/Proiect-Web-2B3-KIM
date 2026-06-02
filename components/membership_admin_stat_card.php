<?php
/**
 * @var string $subscriptionType //venit din admin widget
 * @var numeric $totalActive //tot la fel
 */
?>

<?php
$icons = [
    'fitness' => 'fa-solid fa-dumbbell',
    'physiotherapy' => 'fa-solid fa-heart-pulse',
    'strength' => 'fa-solid fa-weight-hanging',
    'all' => 'fa-solid fa-layer-group'
];

$iconClass = isset($icons[$subscriptionType]) ? $icons[$subscriptionType] : 'fa-solid fa-id-card';
?>

<div class="admin__subscription-stats-card ">

    <div class="admin__subscription-row">

        <div class="admin__subscription-row__left">
            <div class="admin__subscription-row__icon theme-<?= $subscriptionType ?>">
                <i class="fa-solid <?= $iconClass ?>"></i>
            </div>

            <span class="admin__subscription-row__name"><?= ucfirst($subscriptionType)?></span>
        </div>

        <div class="admin__subscription-row__right">
            <span class="admin__subscription-row__count"><?= $totalActive ?></span>
        </div>

    </div>

</div>