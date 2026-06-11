<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Reports & Statistics
    </title>

    <link rel="stylesheet" href="/kim/public/css/global.css">

    <link rel="stylesheet" href="/kim/public/css/reports_css/reports_analytics.css">
    <link rel="stylesheet" href="/kim/public/css/reports_css/reports_distribution.css">
    <link rel="stylesheet" href="/kim/public/css/reports_css/reports_sessions.css">
    <link rel="stylesheet" href="/kim/public/css/reports_css/reports_trainers.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="/kim/public/js/report_sessions.js"></script>
    <script defer src="/kim/public/js/report_trainers.js"></script>
    <script defer src="/kim/public/js/report_distribution.js"></script>

    <link rel="icon" href="/kim/public/images/serenity_icon.svg" type="image/svg+xml">


</head>

<body>

<?php include 'components/headers/admin_header.php'; ?>

<?php include 'components/alert.php'; ?>

<div class="reports">

    <?php include 'components/reportspage/reports_analytics.php'; ?>

    <?php include 'components/reportspage/reports_sessions.php'; ?>

    <?php include 'components/reportspage/reports_trainers.php'; ?>

    <?php include 'components/reportspage/reports_distribution.php'; ?>

</div>

</body>

</html>