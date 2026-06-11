<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404</title>

    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="/kim/public/css/404.css">


    <link rel="icon" href="/kim/public/images/serenity_icon.svg" type="image/svg+xml">

</head>
<body class="login__page">
<div class="error-container">
    <h1>404</h1>
    <h2>Oops! Page Not Found</h2>

    <?php
    $errorMsg = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.";
    ?>

    <p><?= $errorMsg ?></p>

    <a href="/kim/dashboard" class="back-btn">Go to Dashboard</a>
</div>

</body>
</html>
