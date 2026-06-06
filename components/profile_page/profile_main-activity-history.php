<?php
// CELE DE USER VIN DIN PROFILE.PHP, IAR CELELALTE DIN ProfileNotificationsController
/**
 * @var string $userId
 * @var string $userFirstName
 * @var string $userLastName
 * @var string $userJoinDate
 * @var string $userRole
 * @var string $userEmail
 * @var array $groupedSessions  din ProfileActivityController, array cu toate sesiunile grupate pe status
 * $groupedSessions = [
 * 'ongoing' => [],
 * 'planned' => [],
 * 'completed' => [],
 * 'canceled' => []
 * ];
 */

?>

<div class="profile__list">

<?php if(count($groupedSessions['ongoing'])>0) :?>
    <h3>Ongoing Sessions</h3>
    <?php foreach ($groupedSessions['ongoing'] as $session):?>
        <?php include 'components/session_card.php';?>
    <?php endforeach;?>
<?php endif; ?>

<?php if(count($groupedSessions['planned'])>0) :?>
    <h3>Planned Sessions</h3>
    <?php foreach ($groupedSessions['planned'] as $session):?>
        <?php include 'components/session_card.php';?>
    <?php endforeach;?>
<?php endif; ?>

<?php if(count($groupedSessions['completed'])>0) :?>
    <h3>Completed Sessions</h3>
    <?php foreach ($groupedSessions['completed'] as $session):?>
        <?php include 'components/session_card.php';?>
    <?php endforeach;?>
<?php endif; ?>

<?php if(count($groupedSessions['canceled'])>0) :?>
    <h3>Canceled Sessions</h3>
    <?php foreach ($groupedSessions['canceled'] as $session):?>
        <?php include 'components/session_card.php';?>
    <?php endforeach;?>
<?php endif; ?>


</div>
