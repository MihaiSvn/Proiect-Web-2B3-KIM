<?php
/**
 * @var numeric $size //poate fi null, daca nu vreau size ul normal de la header
 */
?>
<?php
$userAvatar = isset($_SESSION['user_profile-picture']) ? $_SESSION['user_profile-picture'] : 'default-avatar.svg';

?>

<div class="header__profile"
        <?php if (isset($size)): ?>
            style="width: <?= $size ?>px;
                    height: <?= $size ?>px;
                    "
        <?php endif; ?>>
    <img src="<?= AVATAR_PATH . htmlspecialchars($userAvatar) ?>" alt="Profile" class="profile__avatar">
</div>
