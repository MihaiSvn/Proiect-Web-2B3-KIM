<?php
/**
 * @var string $userFirstName
 * @var string $userLastName
 * @var string $userJoinDate
 * @var string $userRole
 * @var numeric $size //pentru size la profile pic
 */

//ignoram parametrii si luam url pentru a vedea pe care sa pun active
// adica /kim/profile/personal-info
$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>


<div class="profile__card user-summary">
    <?php include 'components/profile_pic-circle.php'; ?>


    <h3 class="user-summary__name"><?= $userFirstName . ' ' . $userLastName ?></h3>
    <p class="user-summary__role"><?= ucfirst($userRole) ?></p>

</div>

<nav class="profile__card profile-nav">
    <ul class="profile-nav__list">
        <li class="profile-nav__item <?= ($currentUrl === '/kim/profile/personal-info') ? 'active' : '' ?>">
            <a href="/kim/profile/personal-info" class="profile-nav__link">
                <i class="fa-regular fa-user"></i> Personal Information
            </a>
        </li>

        <?php if($userRole==='member'): ?>
        <li class="profile-nav__item <?= ($currentUrl === '/kim/profile/membership-history') ? 'active' : '' ?>">
            <a href="/kim/profile/membership-history" class="profile-nav__link">
                <i class="fa-regular fa-credit-card"></i> Membership History
            </a>
        </li>
        <?php endif;?>
        <li class="profile-nav__item <?= ($currentUrl === '/kim/profile/activity-history') ? 'active' : '' ?>">
            <a href="/kim/profile/activity-history" class="profile-nav__link">
                <i class="fa-solid fa-clock-rotate-left"></i> Activity History
            </a>
        </li>
        <li class="profile-nav__item <?= ($currentUrl === '/kim/profile/notifications') ? 'active' : '' ?>">
            <a href="/kim/profile/notifications" class="profile-nav__link">
                <i class="fa-regular fa-bell"></i> Notifications </a>
        </li>
        <li class="profile-nav__item <?= ($currentUrl === '/kim/profile/settings') ? 'active' : '' ?>">
            <a href="/kim/profile/settings" class="profile-nav__link">
                <i class="fa-solid fa-shield"></i> Settings </a>
        </li>
        <li class="profile-nav__item">
            <a href="/kim/logout" class="profile-nav__link logout-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
            </a>
        </li>
    </ul>
</nav>

<div class="profile__card member-since">
    <h4 class="member-since__title">MEMBER SINCE</h4>
    <p class="member-since__date"> <?= date('M Y', strtotime($userJoinDate)) ?></p>
</div>

