<?php
/**
* @var bool $hasUnread boolean that most headers should have that says wheter the user has unread notifications
 */
?>

<div class="header__actions">
    <button class="header__btn header__btn-notification">
        <i class="fa-regular fa-bell"></i>
        <?php if ($hasUnread): ?>
            <span class="notification__dot"></span>
        <?php endif; ?>
    </button>

    <?php
    $userAvatar = isset($_SESSION['user_profile-picture']) ? $_SESSION['user_profile-picture'] : 'default-avatar.svg';
    ?>

    <div class="header__profile">
        <img src="<?= AVATAR_PATH . htmlspecialchars($userAvatar)?>" alt="Profile" class="profile__avatar">
    </div>
</div>