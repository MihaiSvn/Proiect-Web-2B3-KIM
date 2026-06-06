<?php
/**
 * @var bool $hasUnread boolean that most headers should have that says wheter the user has unread notifications
 */
?>

<div class="header__actions-wrapper">


    <div class="header__actions">
        <button class="header__btn header__btn-notification">
            <i class="fa-regular fa-bell"></i>
            <?php if ($hasUnread): ?>
                <span class="notification__dot"></span>
            <?php endif; ?>
        </button>


        <a href="/kim/profile">
            <?php include 'components/profile_pic-circle.php'; ?>
        </a>
    </div>

    <button class="header__hamburger" id="hamburger-btn">
        <i class="fa-solid fa-bars"></i>
    </button>

</div>
