
<section id="experts" class="experts">

    <div class="experts__header">

        <div>

            <p class="experts__subtitle">
                OUR TEAM
            </p>

            <h2 class="experts__heading">
                Meet Our
                <br>
                <span>Expert Specialists.</span>
            </h2>

        </div>

        <div class="experts__controls">

            <button
                    id="expertsPrev"
                    class="experts__button experts__button--prev">

                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button
                    id="expertsNext"
                    class="experts__button experts__button--next">

                <i class="fa-solid fa-chevron-right"></i>
            </button>

        </div>

    </div>

    <div class="experts__carousel">

        <div class="experts__track">

            <?php foreach ($trainers as $trainer): ?>

                <?php

                $avatar =
                        $trainer->profile_picture
                                ?: 'default-avatar.svg';

                $image =
                        AVATAR_PATH .
                        htmlspecialchars($avatar);

                $name =
                        $trainer->first_name .
                        ' ' .
                        $trainer->last_name;

                $certification = '';

                $role = 'Trainer';

                $specialty =
                        ucfirst($trainer->specialization);

                include __DIR__ . '/../expert_card.php';

                ?>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="experts__dots">

        <span class="experts__dot"></span>
        <span class="experts__dot"></span>
        <span class="experts__dot experts__dot--active"></span>
        <span class="experts__dot"></span>
        <span class="experts__dot"></span>

    </div>

</section>
