<?php

/**
 * @var string $packageTitle
 * @var int $subscriptionId
 * @var string $price
 * @var string $oldPrice
 * @var string $sessions
 * @var string $description
 * @var array $features
 * @var string $buttonText
 * @var bool $featured
 */

?>

<div class="membership-card <?= $featured ? 'membership-card--featured' : '' ?>">

    <?php if($featured): ?>

        <div class="membership-card__badge">
            SAVE 10%
        </div>

    <?php endif; ?>

    <p class="membership-card__title">
        <?= $packageTitle ?>
    </p>

    <div class="membership-card__pricing">

        <span class="membership-card__price">
            <?= $price ?>
        </span>

        <span class="membership-card__per-session">
            / session
        </span>

    </div>

    <p class="membership-card__old-price">
        <?= $oldPrice ?>
    </p>

    <div class="membership-card__sessions">
        <?= $sessions ?>
    </div>

    <p class="membership-card__validity">
        Valid for 2 months
    </p>

    <p class="membership-card__description">
        <?= $description ?>
    </p>

    <ul class="membership-card__features">

        <?php foreach($features as $feature): ?>

            <li class="membership-card__feature">
                <?= $feature ?>
            </li>

        <?php endforeach; ?>

    </ul>

    <button
            type="button"
            class="membership-card__button"
            data-subscription-id="<?= $subscriptionId ?>">

        <?= $buttonText ?>

    </button>

</div>