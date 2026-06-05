<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plans</title>

    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/membership/membership_hero.css">
    <link rel="stylesheet" href="/kim/public/css/membership/membership_compare.css">
    <link rel="stylesheet" href="/kim/public/css/membership/membership_packages.css">
    <link rel="stylesheet" href="/kim/public/css/membership/membership_included.css">
    <link rel="stylesheet" href="/kim/public/css/membership/membership_journey.css">
    <link rel="stylesheet" href="/kim/public/css/member_footer.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>



<?php include 'components/headers/member_header.php'; ?>

<?php include 'components/alert.php'; ?>

<div class="membership__wrapper">

    <?php include 'components/membership_page/membership_hero.php'; ?>

    <?php include 'components/membership_page/membership_compare.php'; ?>

    <?php include 'components/membership_page/membership_packages.php'; ?>

    <?php include 'components/membership_page/membership_included.php'; ?>

    <?php include 'components/membership_page/membership_journey.php'; ?>

</div>

<?php include 'components/footers/member_footer.php'; ?>

<script src="/kim/public/js/membership_cards.js" defer></script>

</body>
</html>