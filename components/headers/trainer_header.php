<?php
// luam url curent sa stim la ce pagina suntem sa coloram
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//vedem daca avem notificari
$hasUnread = isset($unreadNotifications) && count($unreadNotifications) > 0;
?>

<header class="kim__header">
    <div class="header__container">

        <?php include 'components/logo.php';  ?>

        <nav class="header__nav">
            <a href="/kim/dashboard" class="nav__link <?= strpos($currentPath, 'dashboard') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-border-all"></i> Dashboard
            </a>

            <a href="/kim/sessions" class="nav__link <?= strpos($currentPath, 'sessions') !== false ? 'active' : '' ?>">
                <i class="fa-regular fa-clock"></i> Sessions
            </a>
        </nav>

        <?php include 'components/header_actions.php'; ?>

    </div>
</header>