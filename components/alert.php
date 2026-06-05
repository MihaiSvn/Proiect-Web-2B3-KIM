<?php
$alertMessage = '';
$alertType = '';
$icon = '';

// luam string ul query  (ex: "error=abc&success=def&error=xyz")
$queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

//il spargem dupa & ca strtok
$params = explode('&', $queryString);

foreach ($params as $param) {
    // spargem dupa = sa avem 'error' si 'abc'
    $parts = explode('=', $param, 2);
    $key = urldecode($parts[0]);
    $value = isset($parts[1]) ? urldecode($parts[1]) : '';

    if ($key === 'success' && $value !== '') {
        $alertMessage = htmlspecialchars($value);
        $alertType = 'alert__success';
        $icon = 'fa-solid fa-circle-check';
    } elseif ($key === 'error' && $value !== '') {
        $alertMessage = htmlspecialchars($value);
        $alertType = 'alert__error';
        $icon = 'fa-solid fa-circle-exclamation';
    }
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