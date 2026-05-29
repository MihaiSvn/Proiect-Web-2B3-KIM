<?php
$alertMessage = '';
$alertType = '';
$icon = '';

if (isset($_GET['success'])) {
    $alertMessage = htmlspecialchars($_GET['success']);
    $alertType = 'alert__success';
    $icon = 'fa-solid fa-circle-check';
} elseif (isset($_GET['error'])) {
    $alertMessage = htmlspecialchars($_GET['error']);
    $alertType = 'alert__error';
    $icon = 'fa-solid fa-circle-exclamation';
}

?>


<?php if (!empty($alertMessage)): ?>
    <div class="alert <?= $alertType ?>" id="modularAlert">
        <i class="<?= $icon ?>"></i>
        <span><?= $alertMessage ?></span>
        <button class="alert__close" onclick="document.getElementById('modularAlert').classList.add('alert__hidden')">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <script>
        setTimeout(function() {
            const alertBox = document.getElementById('modularAlert');
            if (alertBox) {
                alertBox.classList.add('alert__hidden');
            }
        }, 5000); // 5000 milisecunde = 5 secunde
    </script>
<?php endif; ?>