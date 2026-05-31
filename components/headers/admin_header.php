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
            <a href="/kim/admin/users" class="nav__link <?= strpos($currentPath, 'users') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-user-group"></i> Manage Users
            </a>

            <a href="/kim/admin/specialists" class="nav__link <?= strpos($currentPath, 'specialists') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-user-doctor"></i> Manage Specialists
            </a>

            <a href="/kim/admin/facilities" class="nav__link <?= strpos($currentPath, 'facilities') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-location-dot"></i> Manage Facilities
            </a>

            <a href="/kim/admin/sessions" class="nav__link <?= strpos($currentPath, 'sessions') !== false ? 'active' : '' ?>">
                <i class="fa-regular fa-calendar"></i> Manage Sessions
            </a>
        </nav>

        <?php include 'components/header_actions.php'; ?>

    </div>
</header>