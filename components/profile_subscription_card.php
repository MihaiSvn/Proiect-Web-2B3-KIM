<?php
/**
 * @var object $subscription //venit din profile.php, are toate informatiile despre un abonament, venit din foreach activeSubscriptions
 */
?>

<?php
$expiryDate = strtotime($subscription->end_date);
$daysLeft = floor(($expiryDate - time()) / (60 * 60 * 24));
//                expiryDate - time e diferenta de timp in secunde, imparit la 60 secunde pe minute, *60 minute obtii 3600 secunde intr o ora, *24 de ore, ai 86400 de secunde intr o zi

$icons = [
    'fitness' => 'fa-solid fa-dumbbell',
    'physiotherapy' => 'fa-solid fa-heart-pulse',
    'mixed' => 'fa-solid fa-layer-group'
];

$suspending_days_left = $subscription->suspending_days_left;

$iconClass = isset($icons[$subscription->type]) ? $icons[$subscription->type] : 'fa-solid fa-id-card';
?>
<div class="subscription__card theme-<?= htmlspecialchars($subscription->type) ?>">

    <div class="subscription__header">
        <!--                        sa schimb dinamic icon ul in functie de abonament-->
        <i class="<?= $iconClass ?>"></i>
        <p class="subscription__title"><?= htmlspecialchars($subscription->subscription_name) ?></p>
        <p class="subscription__category"><?= htmlspecialchars(strtoupper($subscription->type)) ?></p>

    </div>

    <div class="subscription__content">
        <div class="subscription__row">
            <i class="fa-regular fa-calendar"></i>
            <p class="subscription__text">Expires on: <?= date('d M Y', $expiryDate) ?></p>
        </div>
        <div class="subscription__row">
            <i class="fa-regular fa-hourglass"></i>
            <p class="subscription__text">Days left:
                <?php if ($daysLeft > 0): ?>
                    <?= $daysLeft ?>
                <?php elseif ($daysLeft === 0): ?>
                    Expires today!
                <?php endif; ?>
            </p>
        </div>
        <div class="subscription__row">
            <i class="fa-regular fa-snowflake"></i>
            <p class="subscription__text">Suspending days
                left: <?= $suspending_days_left ?></p>
        </div>

        <button class="subscription__button" <?php if ($suspending_days_left <= 0): ?>disabled<?php endif; ?>>
            <i class="fa-solid fa-pause"></i>
            Suspend subscription
        </button>
    </div>
</div>
