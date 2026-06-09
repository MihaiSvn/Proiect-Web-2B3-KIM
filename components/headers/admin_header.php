<?php
// luam url curent sa stim la ce pagina suntem sa coloram
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//vedem daca avem notificari
$hasUnread = isset($unreadNotifications) && count($unreadNotifications) > 0;
?>

<header class="kim__header">
    <div class="header__container">

        <?php include 'components/logo.php';  ?>

        <nav class="header__nav" id="mobile-nav">
            <a href="/kim/dashboard" class="nav__link <?= strpos($currentPath, 'dashboard') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-border-all"></i> Dashboard
            </a>
            
            <a href="/kim/users" class="nav__link <?= strpos($currentPath, 'users') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-user-group"></i>  Users
            </a>

            <a href="/kim/specialists" class="nav__link <?= strpos($currentPath, 'specialists') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-user-doctor"></i>  Specialists
            </a>

            <a href="/kim/rooms" class="nav__link <?= strpos($currentPath, 'rooms') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-location-dot"></i>  Rooms
            </a>

            <a href="/kim/sessions" class="nav__link <?= strpos($currentPath, 'sessions') !== false ? 'active' : '' ?>">
                <i class="fa-regular fa-clock"></i>  Sessions
            </a>

            <a href="/kim/memberships" class="nav__link <?= strpos($currentPath, 'membership') !== false ? 'active' : '' ?>">
                <i class="fa-regular fa-calendar"></i>  Memberships
            </a>

            <a href="/kim/equipment" class="nav__link <?= strpos($currentPath, 'equipment') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-dumbbell"></i>  Equipment
            </a>

            <a href="/kim/reports" class="nav__link <?= strpos($currentPath, 'reports') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-area"></i>  Reports
            </a>


        </nav>

        <?php include 'components/header_actions.php'; ?>





    </div>
</header>