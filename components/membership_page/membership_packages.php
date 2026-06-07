<?php

/**
 * @var array $fitnessSubscriptions
 * @var array $strengthSubscriptions
 * @var array $physiotherapySubscriptions
 * @var array $allSubscriptions
 */

function renderMembershipCards($subscriptions)  //parcurge abonamentele si face cardurile pt fiecare tip
{
    foreach ($subscriptions as $subscription) {

        $subscriptionId = $subscription->id;
        $packageTitle = $subscription->name;
        $price = '€' . $subscription->price;
        $oldPrice = '';
        $sessions =$subscription->sessions . ' Sessions';
        $description = $subscription->description;
        $validity = 'Valid for '.$subscription->validity_days .' days';
        $features = $subscription->features;
        $buttonText = 'Select';
        $featured = ($subscription->sessions == 12);
        include __DIR__ . '/../membership_card.php';
    }
}

?>

<section class="membership-packages" id="packages">

    <div class="membership-packages__container">

        <p class="membership-packages__subtitle">
            MEMBERSHIP PACKAGES
        </p>

        <h2 class="membership-packages__title">
            Pick Your Package
        </h2>

        <p class="membership-packages__description">
            Choose the programme that best matches your goals and commitment level.
        </p>

        <div class="membership-packages__tabs">

            <button class="membership-packages__tab membership-packages__tab--active">
                Fitness
            </button>

            <button class="membership-packages__tab">
                Strength Training
            </button>

            <button class="membership-packages__tab">
                Physiotherapy
            </button>

            <button class="membership-packages__tab">
                Full Access
            </button>

        </div>

        <div class="membership-packages__panel membership-packages__panel--active">

            <div class="membership-packages__grid">

                <?php renderMembershipCards($fitnessSubscriptions); ?>

            </div>

        </div>

        <div class="membership-packages__panel">

            <div class="membership-packages__grid">

                <?php renderMembershipCards($strengthSubscriptions); ?>

            </div>

        </div>

        <div class="membership-packages__panel">

            <div class="membership-packages__grid">

                <?php renderMembershipCards($physiotherapySubscriptions); ?>

            </div>

        </div>

        <div class="membership-packages__panel">

            <div class="membership-packages__grid">

                <?php renderMembershipCards($allSubscriptions); ?>

            </div>

        </div>

    </div>

</section>