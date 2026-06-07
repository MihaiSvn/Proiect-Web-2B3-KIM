<?php
/**
 * @var object $user //obiect de tip user cu date din db
 * @var string $headerMainTitle //titlul din cardul principal
 * @var string $headerMainSubtitle //subtitulul
 * @var string $pagePath //path ul catre componenta de content efectiv, cum ar fi personal info, history etc
 * CE VINE AICI DEPINDE DE PE CE PAGINA ESTI, DEPINDE DIN CE CONTROLLER A FOST APELAT
 * components/profile_page/profile_main-personal-info.php de exemplu
 * CE E MAI SUS VINE DE PE ORICARE PAGINA DE PROFILE
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
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/profile.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/subscription_card.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/forms.css?v=1.2">
    <link rel="stylesheet" href="/kim/public/css/notifications.css">
    <link rel="stylesheet" href="/kim/public/css/session_card.css">
    <script src="/kim/public/js/update_profile_preview.js" defer></script>
    <script src="/kim/public/js/popup.js" defer></script>
    <script src="/kim/public/js/auth_buttons_listeners/logout.js" defer></script>
    <script src="/kim/public/js/profile_button_listeners/save_personal_info.js" defer></script>
    <script src="/kim/public/js/profile_button_listeners/save_change_password.js" defer></script>
    <script src="/kim/public/js/profile_button_listeners/notification_dismiss.js" defer></script>
</head>
<body>
<?php
$userId = $user->id;
$userFirstName = $user->first_name;
$userLastName = $user->last_name;
$userEmail = $user->email;
$userRole = $user->role;
$userJoinDate = $user->created_at;
?>
<?php include 'components/header.php'; ?>

<?php include 'components/alert.php'; ?>
<div class="profile-page">


    <?php
    $size = 70;  //pentru size la profile pic
    ?>
    <aside class="profile__sidebar">
        <?php include 'components/profile_page/profile_sidebar.php'; ?>
    </aside>
    <main class="profile__content">
        <div class="profile__card main-card">

            <div class="main-card__header">
                <h2><?=$headerMainTitle?></h2>
                <p><?=$headerMainSubtitle?></p>
            </div>

            <?php include $pagePath; ?>

        </div>
    </main>

</div>
</body>
</html>