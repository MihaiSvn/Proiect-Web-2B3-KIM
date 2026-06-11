<?php
/**
 * @var object $user
 *
 * STRUCTURA UNUI OBIECT UTILIZATOR (USER):
 *
 * @var int    $id
 * @var string $first_name
 * @var string $last_name
 * @var string $email
 * @var string $role
 * @var string $joined_at
 * @var string|null $profile_picture
 * @var array  $memberships        Un array de obiecte cu abonamentele asociate
 *
 * * STRUCUTRA ABONAMENT:
 *
 * @var string $name
 * @var string $type
 * @var string $status
 * @var int    $sessions_left
 * @var int    $suspending_days_left
 */
?>

<?php

$icons_roles = [
    'admin'   => '<i class="fa-solid fa-user-shield"></i>',
    'trainer' => '<i class="fa-solid fa-dumbbell"></i>',
    'member'  => '<i class="fa-regular fa-user"></i>'
];
?>

<tr class="table_<?=$user->role?>">
    <td class="no-wrap">
        <div class="user-info">
            <?php
            $userAvatar = isset($user->profile_picture) ? $user->profile_picture : 'default-avatar.svg';
            ?>

            <div class="header__profile">
                <img src="<?= AVATAR_PATH . htmlspecialchars($userAvatar) ?>" alt="Profile" class="profile__avatar">
            </div>

            <span class="user-name"><?=$user->first_name . ' ' . $user->last_name?></span>
        </div>
    </td>
    <td class="no-wrap"><?=$user->email?></td>
    <td class="no-wrap">
                        <span class="role-badge">
                            <?= $icons_roles[$user->role]?>
                            <?= $user->role?></span>
    </td>
    <td>
        <div class="memberships-list">
            <?php if(count($user->memberships)>0): ?>
                <?php foreach($user->memberships as $membership): ?>
                    <span class="pill theme-<?= htmlspecialchars($membership->type) ?>"><?=$membership->name?></span>

                <?php endforeach;?>
            <?php else: ?>
                No active memberships

            <?php endif;?>
        </div>
    </td>
    <td class="no-wrap"><?= date('M j Y', strtotime($user->joined_at) )  ?></td>
    <td class="no-wrap">
        <div class="action-buttons">
            <button class="action-btn edit-btn js-open-popup" data-target="popupOverlay_edit_<?= $user->id ?>"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="action-btn delete-btn js-open-popup" data-target="popupOverlay_delete_<?= $user->id ?>"><i class="fa-regular fa-trash-can"></i></button>

            <?php
            $popupId = 'popupOverlay_edit_' . $user->id;

            $infoText = null;
            $title = 'Edit User: ' . htmlspecialchars($user->first_name . ' ' . $user->last_name);
            $action = '/kim/api/user/edit-admin';
            $submit = 'Save Changes';

            $idField = FormField::create('User ID', 'user_id')
                ->type('hidden')
                ->value($user->id);

            $firstNameField = FormField::create('First Name', 'first_name')
                ->type('text')
                ->required(true)
                ->value($user->first_name);

            $lastNameField = FormField::create('Last Name', 'last_name')
                ->type('text')
                ->required(true)
                ->value($user->last_name);

            $emailField = FormField::create('Email Address', 'email')
                ->type('email')
                ->required(true)
                ->value($user->email);

            $roleOptions = [
                'member'  => 'Member',
                'trainer' => 'Trainer',
                'admin'   => 'Administrator'
            ];
            $roleField = FormField::create('Role', 'role')
                ->type('select')
                ->required(true)
                ->options($roleOptions)
                ->value($user->role);

            $specOptions = [
                'fitness' => 'Fitness',
                'physiotherapy' => 'Physiotherapy',
                'strength' => 'Strength'
            ];
            $specField = FormField::create('Specialization', 'specialization')
                ->type('select')
                ->placeholder('Select specialization')
                ->options($specOptions);

            if (isset($user->specialization)) {
                $specField->value($user->specialization);
            }

            $formBody = [
                $idField,
                $firstNameField,
                $lastNameField,
                $emailField,
                $roleField,
                $specField
            ];

            include 'components/popup.php';
            ?>

            <?php

            $infoText=null;
            $popupId = 'popupOverlay_delete_' . $user->id;
            $title = 'Delete Account';
            $submit = 'Yes, Delete';
            $action = '/kim/api/user/delete';
            $infoText = "Are you sure you want to delete the account for <b>" . htmlspecialchars($user->first_name . ' ' . $user->last_name) . "</b>? This action is permanent and cannot be undone.";

            $deleteIdField = FormField::create('', 'user_id')->type('hidden')->value($user->id);

            $formBody = [$deleteIdField];

            include 'components/popup.php';


            ?>


        </div>
    </td>
</tr>
