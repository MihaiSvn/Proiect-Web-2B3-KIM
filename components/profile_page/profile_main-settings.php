<?php
// CELE DE USER VIN DIN PROFILE.PHP, IAR CELELALTE DIN ProfileNotificationsController
/**
 * @var string $userId
 * @var string $userFirstName
 * @var string $userLastName
 * @var string $userJoinDate
 * @var string $userRole
 * @var string $userEmail
 */

?>

<div class="profile__list">
    <h3><i class="fa-solid fa-lock"></i> Change password</h3>
    <form id="changePasswordForm"  method="POST">
        <div class="profile__list">

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••">
            </div>

            <div class="profile-form__actions">
                <button type="submit" class="btn btn--primary">
                    <i class="fa-regular fa-floppy-disk"></i> Save Changes
                </button>
            </div>

        </div>

    </form>

    <script>
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('/kim/api/user/change-password', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {

                    const redirectUrl = '/kim/profile/settings?' + result.status + '=' + encodeURIComponent(result.message);

                    window.location.href = redirectUrl;
                })
                .catch(error => {
                    window.location.href = '/kim/profile/settings?error=Service unavailable';
                });
        });
    </script>

</div>

<?php if($userRole==='member'): ?>
<div class="divider"></div>

<div class="profile__list">
    <h3><i class="fa-solid fa-trash"></i> Delete Account</h3>
    <p>Once you delete your account, all your personal data and session records will be permanently
        removed. This action cannot be undone.</p>


    <?php

    $title = 'Delete Account';
    $submit = 'Yes';
    $action = '/kim/api/user/delete';
    $popupId = 'popupOverlay_deleteUser_' . $userId;
    $infoText = "Are you sure you want to delete your account? This action is permanent and cannot be undone.";
    require_once __DIR__ . '/../../classes/FormField.php';



    $sessionIdField = FormField::create('', 'user_id')->type('hidden')->value($userId);


    $formBody = [$sessionIdField];
    ?>

    <?php include 'components/popup.php' ?>

    <button type="button" class="session__btn session__btn--danger js-open-popup"
            data-target="popupOverlay_deleteUser_<?= $userId ?>"
    >
        Delete Account
    </button>
</div>

<?php endif;?>
