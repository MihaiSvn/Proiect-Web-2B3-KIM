<?php
/**
 * * DATELE VIN DIN AdminUsersController
 * * @var object[] $admins   Lista tuturor administratorilor
 * @var object[] $trainers Lista tuturor antrenorilor
 * @var object[] $members  Lista tuturor membrilor
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/forms.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/manage_users.css">
    <link rel="stylesheet" href="/kim/public/css/subscription_card.css">
    <script src="/kim/public/js/popup.js" defer></script>
    <script src="/kim/public/js/header.js" defer></script>
    <script src="/kim/public/js/manage_users.js" defer"></script>
</head>
<body>

</body>
</html>


<?php include 'components/alert.php';?>
<?php include "components/header.php";?>

<div class="management-container">

    <header class="management__header">
        <div class="management__titles">
            <h1 class="management__title">Users Management</h1>
            <p class="management__subtitle">Manage members, trainers, therapists, and administrators.</p>
        </div>
        <div class="management__actions">

            <?php
            require_once __DIR__ . '/../classes/FormField.php';

            $title = 'Add New User';
            $action = '/kim/api/user/create';
            $submit = 'Create User';
            $popupId = 'popupOverlay_createUser';

            $firstNameField = FormField::create('First Name', 'first_name')
                    ->type('text')
                    ->required(true)
                    ->placeholder('John');

            $lastNameField = FormField::create('Last Name', 'last_name')
                    ->type('text')
                    ->required(true)
                    ->placeholder('Doe');

            $emailField = FormField::create('Email Address', 'email')
                    ->type('email')
                    ->required(true)
                    ->placeholder('john.doe@example.com');

            $passwordField = FormField::create('Password', 'password')
                    ->type('password')
                    ->required(true)
                    ->placeholder('Minimum 6 characters');

            $confirmPasswordField = FormField::create('Confirm Password', 'confirm_password')
                    ->type('password')
                    ->required(true)
                    ->placeholder('Repeat password');

            $roleOptions = [
                    'member'  => 'Member',
                    'trainer' => 'Trainer',
                    'admin'   => 'Admin'
            ];

            $roleField = FormField::create('Role', 'role')
                    ->type('select')
                    ->required(true)
                    ->placeholder('Select a role')
                    ->options($roleOptions);

            $specializationOptions = [
                    'fitness' => 'Fitness',
                    'physiotherapy' => 'Physiotherapy',
                    'strength' => 'Strength'
            ];

            $specializationField = FormField::create('Specialization', 'specialization')
                    ->type('select')
                    ->placeholder('Select a specialization')
                    ->options($specializationOptions);

            $formBody = [
                    $firstNameField,
                    $lastNameField,
                    $emailField,
                    $passwordField,
                    $confirmPasswordField,
                    $roleField,
                    $specializationField
            ];
            ?>

            <?php include 'components/popup.php'; ?>

            <button
                    type="button"
                    class="btn btn--primary js-open-popup"
                    data-target="popupOverlay_createUser">

                <i class="fa-solid fa-plus"></i>
                Add User

            </button>

            <?php

            $title = 'Import Trainers';
            $action = '/kim/api/trainers/import';
            $submit = 'Import';
            $popupId = 'popupOverlay_importTrainers';

            $fileField = FormField::create(
                    'File',
                    'file'
            )
                    ->type('file')
                    ->required(true);

            $formatField = FormField::create(
                    'Format',
                    'format'
            )
                    ->type('select')
                    ->required(true)
                    ->options([
                            'csv' => 'CSV',
                            'xml' => 'XML'
                    ]);

            $formBody = [
                    $formatField,
                    $fileField
            ];

            ?>

            <?php include 'components/popup.php'; ?>

            <button
                    type="button"
                    class="btn btn--outline js-open-popup"
                    data-target="popupOverlay_importTrainers">

                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                Import Data

            </button>

            <div class="dropdown">

                <button
                        type="button"
                        class="btn btn--outline">

                    <i class="fa-solid fa-arrow-down"></i>
                    Export Data
                    <i class="fa-solid fa-chevron-down"></i>

                </button>

                <div class="dropdown-content">

                    <a href="/kim/api/trainers/export/csv">

                        <i class="fa-solid fa-file-csv"></i>
                        Export Trainers CSV

                    </a>

                    <a href="/kim/api/trainers/export/xml">

                        <i class="fa-solid fa-file-code"></i>
                        Export Trainers XML

                    </a>

                </div>

            </div>

        </div>
    </header>

    <section class="management__filters">
        <div class="filter__dropdowns">
            <select class="filter__select" id="roleFilter">
                <option value="all">Role: All Users</option>
                <option value="member">Member</option>
                <option value="trainer">Trainer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
    </section>

    <section class="management__table-wrapper">
        <table class="management__table">
            <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Memberships</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($members as $user): ?>
                <?php include "components/table_row_users.php"?>
            <?php endforeach;?>

            <?php foreach($trainers as $user): ?>
                <?php include "components/table_row_users.php"?>
            <?php endforeach;?>

            <?php foreach($admins as $user): ?>
                <?php include "components/table_row_users.php"?>
            <?php endforeach;?>

            </tbody>
        </table>
    </section>

</div>
