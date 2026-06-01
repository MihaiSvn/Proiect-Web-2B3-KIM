<?php

/** @var string $image */
/** @var string $name */
/** @var string $certification */
/** @var string $role */
/** @var string $specialty */

?>

<div class="expert-card">

    <img
        src="<?= $image ?>"
        alt="<?= $name ?>"
        class="expert-card__image">

    <div class="expert-card__overlay">

        <h3 class="expert-card__name">
            <?= $name ?>
        </h3>

        <p class="expert-card__role">
            <?= $role ?>
        </p>

        <p class="expert-card__specialty">
            <i class="fa-solid fa-award"></i>
            <?= $specialty ?>
        </p>

    </div>

</div>