<?php
/**
 * @var numeric $trend // it can be null, if it exists it's a number
 * @var string $cardIcon // icon for the card
 * @var string $iconColor // color for icon
 * @var string $iconBgColor //color pt background icon
 * @var string $statTitle //title for card
 * @var string $statText // actual stat
 */
?>


<?php

//trend sa fie un numar
    if(isset($trend)){
        if($trend<0){
            $trendClass = 'trend-down';
            $trendText = $trend  . "%";
        } else {
            $trendClass = 'trend-up';
            $trendText = "+" . $trend  . "%";
        }
    }

?>
<div class="stat-card">
    <div class="stat-card__header">
        <div class="stat-card__icon" style="color: <?= $iconColor ?>; background-color: <?= $iconBgColor ?>">
            <i class="<?= $cardIcon ?>"></i>
        </div>
        <?php if(isset($trend)): ?>
        <div class="stat-card__trend <?= $trendClass ?>">
            <?= $trendText ?>
        </div>
        <?php endif;?>
    </div>

    <div class="stat-card__body">
        <p class="stat-card__label"><?= $statTitle?></p>
        <h3 class="stat-card__value"> <?= $statText ?></h3>

    </div>
</div>