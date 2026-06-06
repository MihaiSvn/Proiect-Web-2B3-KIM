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

                <?php

                $packageTitle = 'Starter';
                $price = '€89';
                $oldPrice = '';
                $sessions = '4 Sessions';

                $description =
                        'Perfect for trying out a programme or returning after a break.';

                $features = [
                        '4 booked sessions',
                        'Progress check-in',
                        'Facility access',
                        'Specialist assignment'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Standard';
                $price = '€79';
                $oldPrice = '€89 / session';
                $sessions = '8 Sessions';

                $description =
                        'Our most popular option for steady progress and flexibility.';

                $features = [
                        '8 booked sessions',
                        'Detailed progress report',
                        'Priority scheduling',
                        'Specialist assignment',
                        'Nutrition guide'
                ];

                $buttonText = 'Select';
                $featured = true;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Premium';
                $price = '€71';
                $oldPrice = '€89 / session';
                $sessions = '12 Sessions';

                $description =
                        'Maximum commitment, maximum results and the best value.';

                $features = [
                        '12 booked sessions',
                        'Monthly progress review',
                        'Flexible rescheduling',
                        'Specialist assignment',
                        'Nutrition guide',
                        'Recovery plan'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

            </div>

        </div>

        <div class="membership-packages__panel">

            <div class="membership-packages__grid">

                <?php

                $packageTitle = 'Starter';
                $price = '€99';
                $oldPrice = '';
                $sessions = '4 Sessions';

                $description =
                        'Build confidence and technique with guided strength training sessions.';

                $features = [
                        '4 strength sessions',
                        'Movement assessment',
                        'Gym access',
                        'Specialist assignment'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Standard';
                $price = '€89';
                $oldPrice = '€99 / session';
                $sessions = '8 Sessions';

                $description =
                        'Ideal for consistent progress, improved strength and accountability.';

                $features = [
                        '8 strength sessions',
                        'Progress tracking',
                        'Priority booking',
                        'Technique coaching',
                        'Nutrition guidance'
                ];

                $buttonText = 'Select';
                $featured = true;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Premium';
                $price = '€79';
                $oldPrice = '€99 / session';
                $sessions = '12 Sessions';

                $description =
                        'Our most complete strength package with maximum value per session.';

                $features = [
                        '12 strength sessions',
                        'Monthly review',
                        'Flexible scheduling',
                        'Advanced coaching',
                        'Nutrition guidance',
                        'Recovery support'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

            </div>

        </div>

        <div class="membership-packages__panel">

            <div class="membership-packages__grid">

                <?php

                $packageTitle = 'Recovery Basic';
                $price = '€110';
                $oldPrice = '';
                $sessions = '4 Sessions';

                $description =
                        'Focused rehabilitation and pain management with expert guidance.';

                $features = [
                        '4 physio sessions',
                        'Initial assessment',
                        'Recovery exercises',
                        'Treatment plan'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Recovery Plus';
                $price = '€100';
                $oldPrice = '€110 / session';
                $sessions = '8 Sessions';

                $description =
                        'A balanced recovery programme for long-term rehabilitation goals.';

                $features = [
                        '8 physio sessions',
                        'Progress monitoring',
                        'Manual therapy',
                        'Recovery plan',
                        'Priority scheduling'
                ];

                $buttonText = 'Select';
                $featured = true;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Recovery Complete';
                $price = '€90';
                $oldPrice = '€110 / session';
                $sessions = '12 Sessions';

                $description =
                        'Comprehensive physiotherapy support with ongoing progress reviews.';

                $features = [
                        '12 physio sessions',
                        'Advanced treatment',
                        'Manual therapy',
                        'Recovery programme',
                        'Flexible booking',
                        'Priority support'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

            </div>

        </div>

        <div class="membership-packages__panel">

            <div class="membership-packages__grid">

                <?php

                $packageTitle = 'Full Access Starter';
                $price = '€129';
                $oldPrice = '';
                $sessions = '4 Sessions';

                $description =
                        'Combine fitness, strength and recovery in one flexible programme.';

                $features = [
                        '4 mixed sessions',
                        'Facility access',
                        'Specialist assignment',
                        'Progress tracking'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Full Access Plus';
                $price = '€115';
                $oldPrice = '€129 / session';
                $sessions = '8 Sessions';

                $description =
                        'The perfect balance between training, recovery and long-term progress.';

                $features = [
                        '8 mixed sessions',
                        'Nutrition guidance',
                        'Recovery support',
                        'Priority booking',
                        'Progress reviews'
                ];

                $buttonText = 'Select';
                $featured = true;

                include __DIR__ . '/../membership_card.php';

                ?>

                <?php

                $packageTitle = 'Full Access Premium';
                $price = '€105';
                $oldPrice = '€129 / session';
                $sessions = '12 Sessions';

                $description =
                        'Unlimited flexibility and the most complete wellness experience.';

                $features = [
                        '12 mixed sessions',
                        'Nutrition guidance',
                        'Recovery programme',
                        'Manual therapy',
                        'Flexible scheduling',
                        'Dedicated specialist'
                ];

                $buttonText = 'Select';
                $featured = false;

                include __DIR__ . '/../membership_card.php';

                ?>

            </div>

        </div>

    </div>

</section>