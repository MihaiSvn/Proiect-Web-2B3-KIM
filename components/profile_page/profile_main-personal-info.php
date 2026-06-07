<?php
/**
 *
 * @var string $userFirstName
 * @var string $userLastName
 * @var string $userJoinDate
 * @var string $userRole
 * @var string $userEmail
 *
 * @var numeric $size //pentru size la profile pic
 */
?>

<form method="POST" enctype="multipart/form-data" id="updateProfileForm">
    <div class="photo-upload">
        <!--                    NU FOLSOESC COMPONENTA CA SA POT PUNE ID-UL DORIT PT PREVIEW si sa fie mai mare-->
        <?php
        $userAvatar = isset($_SESSION['user_profile-picture']) ? $_SESSION['user_profile-picture'] : 'default-avatar.svg';
        ?>

        <div class="header__profile" style="height: <?= $size ?>px; width: <?= $size ?>px;">
            <img src="<?= AVATAR_PATH . htmlspecialchars($userAvatar) ?>" alt="Profile" class="profile__avatar"
                 id="avatar_preview">
        </div>
        <div class="photo-upload__info">
            <h4>Profile Photo</h4>
            <p>JPG or PNG &bull; max 5 MB</p>

            <input type="file" id="profile_upload" name="profile_picture" accept=".jpg, .jpeg, .png"
                   style="display: none">
            <label for="profile_upload" class="btn btn--photo" style="display: inline-block; cursor: pointer;">
                Change photo
            </label>
        </div>
    </div>

    <div class="divider"></div>


    <div class="profile-form__grid">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= $userFirstName ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= $userLastName ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= $userEmail ?>" required>
        </div>

    </div>

    <div class="divider"></div>

    <div class="profile-form__actions">
        <button type="submit" class="btn btn--primary">
            <i class="fa-regular fa-floppy-disk"></i> Save Changes
        </button>
    </div>

</form>

<script>
    document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        fetch('/kim/api/user/update', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                const redirectUrl = window.location.pathname + '?' + result.status + '=' + encodeURIComponent(result.message);
                window.location.href = redirectUrl;
            })
            .catch(error => {
                window.location.href = window.location.pathname + '?error=Service unavailable';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
    });
</script>
