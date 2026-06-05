<?php
//array pt casutele tabelului ca sa nu mai pun eu date
$features = [

    ['Personalised programme', true, true, true, true],
    ['Specialist assignment', true, true, true, true],
    ['Facility access', true, true, true, true],
    ['Progress reports', true, true, true, true],

    ['Nutrition guidance', true, true, false, true],
    ['Manual therapy', false, false, true, true],
    ['Recovery plan', false, false, true, true],
    ['Compound lifting focus', false, true, false, true],

    ['Dedicated account manager', false, false, false, true],
    ['Cross-discipline access', false, false, false, true]

];

?>

<section class="membership-compare" id="compare">

    <div class="membership-compare__container">

        <p class="membership-compare__subtitle">
            COMPARE PROGRAMMES
        </p>

        <h2 class="membership-compare__title">
            Find Your Fit
        </h2>

        <div class="membership-compare__table-wrapper">

            <table class="membership-compare__table">

                <thead>

                <tr>

                    <th></th>

                    <th class="membership-compare__plan membership-compare__plan--fitness">
                        Fitness
                    </th>

                    <th class="membership-compare__plan membership-compare__plan--strength">
                        Strength
                    </th>

                    <th class="membership-compare__plan membership-compare__plan--physio">
                        Physio
                    </th>

                    <th class="membership-compare__plan membership-compare__plan--all">
                        Full Access
                    </th>

                </tr>

                </thead>

                <tbody>

                <?php foreach($features as $feature): ?>

                    <tr>

                        <td class="membership-compare__feature">
                            <?= $feature[0] ?>
                        </td>

                        <td><?= $feature[1] ? '✓' : '—' ?></td>

                        <td><?= $feature[2] ? '✓' : '—' ?></td>

                        <td><?= $feature[3] ? '✓' : '—' ?></td>

                        <td><?= $feature[4] ? '✓' : '—' ?></td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>