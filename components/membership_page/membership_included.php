<?php

$benefits = [

    [
        'icon' => 'fa-regular fa-calendar',
        'title' => 'Flexible Scheduling',
        'description' =>
            'Book sessions around your life with easy online scheduling.'
    ],

    [
        'icon' => 'fa-solid fa-user-group',
        'title' => 'Expert Specialists',
        'description' =>
            'Certified trainers and physiotherapists with years of experience.'
    ],

    [
        'icon' => 'fa-solid fa-arrow-trend-up',
        'title' => 'Progress Tracking',
        'description' =>
            'Track every milestone with detailed session reports and metrics.'
    ],

    [
        'icon' => 'fa-solid fa-shield-heart',
        'title' => 'Recovery Monitoring',
        'description' =>
            'Ongoing wellness checks between sessions to keep you on track.'
    ],

    [
        'icon' => 'fa-solid fa-location-dot',
        'title' => 'Facility Access',
        'description' =>
            'Use any KIM facility included in your active membership.'
    ],

    [
        'icon' => 'fa-regular fa-clock',
        'title' => 'No Hidden Fees',
        'description' =>
            'Transparent pricing with no surprise charges or long contracts.'
    ]

];

?>

<section class="membership-included">

    <div class="membership-included__container">

        <p class="membership-included__subtitle">
            WHAT'S INCLUDED
        </p>

        <h2 class="membership-included__title">
            Every Membership Includes
        </h2>

        <div class="membership-included__grid">

            <?php foreach($benefits as $benefit): ?>

                <article class="membership-included__card">

                    <div class="membership-included__icon">

                        <i class="<?= $benefit['icon'] ?>"></i>

                    </div>

                    <h3 class="membership-included__card-title">
                        <?= $benefit['title'] ?>
                    </h3>

                    <p class="membership-included__card-description">
                        <?= $benefit['description'] ?>
                    </p>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>