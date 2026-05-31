<?php
/**
 * @var object $subscription //venit din member_dashboard.php, are toate informatiile despre un abonament, venit din foreach activeSubscriptions
 * la fiecare profile subscription card trebuie sa generezi si popup-ul (vezi popup.php)
 */
?>

<?php
$expiryDate = strtotime($subscription->end_date);
$daysLeft = floor(($expiryDate - time()) / (60 * 60 * 24));
//                expiryDate - time e diferenta de timp in secunde, imparit la 60 secunde pe minute, *60 minute obtii 3600 secunde intr o ora, *24 de ore, ai 86400 de secunde intr o zi

$icons = [
        'fitness' => 'fa-solid fa-dumbbell',
        'physiotherapy' => 'fa-solid fa-heart-pulse',
        'strength' => 'fa-solid fa-weight-hanging',
        'all' => 'fa-solid fa-layer-group'
];

$suspending_days_left = $subscription->suspending_days_left;
$sessions_left = $subscription->sessions_left;

$iconClass = isset($icons[$subscription->type]) ? $icons[$subscription->type] : 'fa-solid fa-id-card';
?>
<div class="subscription__card theme-<?= htmlspecialchars($subscription->type) ?>">

    <i class="<?= $iconClass ?> subscription__watermark"></i>

    <div class="subscription__top">
        <div class="subscription__top-icon">
            <i class="<?= $iconClass ?>"></i>
        </div>
    </div>

    <div class="subscription__main">
        <h3 class="subscription__title"><?= htmlspecialchars($subscription->subscription_name) ?></h3>
        <p class="subscription__location">
            <i class="fa-solid fa-location-dot"></i>
            <?= htmlspecialchars(ucfirst($subscription->type)) ?> Zone
        </p>

        <div class="subscription__details-list">
            <div class="subscription__row">
                <i class="fa-regular fa-hourglass"></i>
                <span>Days left:
                    <strong>
                        <?php if ($daysLeft > 0): ?>
                            <?= $daysLeft ?>
                        <?php elseif ($daysLeft === 0): ?>
                            Expires today!
                        <?php else: ?>
                            Expired
                        <?php endif; ?>
                    </strong>
                </span>
            </div>

            <div class="subscription__row">
                <i class="fa-solid fa-ticket"></i>
                <span>Sessions left: <strong><?= htmlspecialchars($sessions_left) ?></strong></span>
            </div>

            <div class="subscription__row">
                <i class="fa-regular fa-snowflake"></i>
                <span>Suspending days: <strong><?= $suspending_days_left ?></strong></span>
            </div>
        </div>
    </div>

    <div class="subscription__footer-info">
        <div class="subscription__status-badge">
            <span class="status-dot"></span> Active
        </div>
        <div class="subscription__expiry">
            <?= date('d M Y', $expiryDate) ?>
        </div>
    </div>

    <!--        data-target = popupOverlay_id pentru a putea sa gasesc popup-ul specific in js-->
    <button class="subscription__button js-open-popup"
            data-target="popupOverlay_<?= $subscription->id ?>"
            <?php if ($suspending_days_left <= 0): ?>disabled<?php endif; ?>>
        <i class="fa-solid fa-pause"></i>
        Suspend subscription
    </button>
</div>

