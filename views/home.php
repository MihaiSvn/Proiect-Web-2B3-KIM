<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/kim/public/css/home/home_hero.css">
    <link rel="stylesheet" href="/kim/public/css/home/home_services.css">
    <link rel="stylesheet" href="/kim/public/css/home/home_howitworks.css">
    <link rel="stylesheet" href="/kim/public/css/home/home_specialists.css">
    <link rel="stylesheet" href="/kim/public/css/home_footer.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="/kim/public/js/services.js" defer></script>
    <script src="/kim/public/js/specialists_carousel.js" defer></script>
    <script src="/kim/public/js/header.js" defer></script>
</head>




<body>

<?php include 'components/header.php'; ?>

<?php include 'components/alert.php'; ?>

<div class="homepage__wrapper">

    <?php include 'components/homepage/home_hero.php'; ?>

    <?php include 'components/homepage/home_services.php'; ?>

    <?php include 'components/homepage/home_howitworks.php'; ?>

    <?php include 'components/homepage/home_specialists.php'; ?>

</div>


    <?php include 'components/footers/home_footer.php'; ?>

</body>
</html>
