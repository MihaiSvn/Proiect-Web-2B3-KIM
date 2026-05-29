<?php
/**
 * @var object $user //venit din ProfileController, are toate informatiile despre utilizatorul logat in sesiune
 * @var array $activeSubscriptions //venit din ProfileController, are array de obiecte de tip UserSubscription
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.2">
    <link rel="stylesheet" href="/kim/public/css/profile_subscriptions.css">
    <script src="/kim/public/js/popup.js" defer></script>
    <!--    defer asteapta ca codul html sa se incarca ca apoi sa ruleze script ul-->
</head>
<body>

<?php include 'components/alert.php'; ?>
<div class="profile__card">
    <h1>Hello,
        <?php echo htmlspecialchars($user->first_name); ?>
    </h1>

    <div class="profile__infogroup">
        <label>First Name</label>
        <p><?= htmlspecialchars($user->first_name) ?></p>
    </div>
    <div class="profile__infogroup">
        <label>Last Name</label>
        <p><?= htmlspecialchars($user->last_name) ?></p>
    </div>

    <div class="profile__infogroup">
        <label>Role</label>
        <p><span class="profile__rolebadge"><?= htmlspecialchars($user->role) ?></span></p>
    </div>

    <div class="profile__infogroup">
        <label>Member since</label>
        <!--            d:28  M: May Y: 2026, D ar fi Thu., m ar fi 05, y ar fi 26-->
        <p><?= date('d M Y', strtotime($user->created_at)) ?></p>
    </div>
</div>

<?php if ($user->role == 'member'): ?>
    <div class="profile__card">
        <h2>Active subscriptions</h2>

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

                    require_once __DIR__ . '/../classes/FormField.php';

                    $formBody = [
                            new FormField(
                                    'Days to suspend',
                                    'suspend_days',
                                    'number',
                                    true,
                                    '1',
                                    '',
                                    1,
                                    $subscription->suspending_days_left
                            )
                    ];

                    $popupId = 'popupOverlay_' . $subscription->id; //pt a o putea gasi in js

                    include 'components/popup.php';
                    ?>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

<?php endif; ?>

</body>
</html>