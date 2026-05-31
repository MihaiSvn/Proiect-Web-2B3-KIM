<?php
// luam url curent sa stim la ce pagina suntem sa coloram

//vedem daca avem notificari
$hasUnread = isset($unreadNotifications) && count($unreadNotifications) > 0;
?>

<header class="kim__header">
    <div class="header__container">

        <?php include 'components/logo.php';  ?>

        <nav class="header__nav">
            <a href="" class="nav__link">
                Home
            </a>

            <a href="" class="nav__link">
                Services
            </a>

            <a href="" class="nav__link">
                 Experts
            </a>
        </nav>

        <div class="header__actions">
            <a href="/kim/login" class="header__link-simple">
                Login
            </a>

            <a href="/kim/register" class="header__btn-primary">
                Join Now
            </a>
        </div>

    </div>
</header>