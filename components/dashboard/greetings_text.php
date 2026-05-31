<?php
/**
 * @var object $user //venit din DashboardController, obiect cu elemente user
 */
?>

<section class="greetings_section">
    <?php
    $hourNow = (int)date("H");
    $greetingsText = 'Good Morning';
    if($hourNow >= 12 && $hourNow < 18){
        $greetingsText = 'Good Afternoon';
    } else if($hourNow >= 18){
        $greetingsText = 'Good Evening';
    } else if($hourNow < 6){
        $greetingsText = 'Good Night';
    }

    $subtitleText = 'Continue your fitness and recovery journey.';
    if($user->role==='admin'){
        $subtitleText = "Here's what's happening at Serenity today.";
    } else if($user->role==='trainer'){
        $subtitleText = 'Ready to guide and inspire your clients today.';
    }
    ?>

    <div class="greetings__container">
        <h1 class="greetings__title">
            <!--            &#xFE0E; renunta la culori si l deseneaza alb negru-->
            <span class="emoji__sparkle">✨&#xFE0E;</span>

            <div class="greetings__text-wrapper">
                <?= $greetingsText ?>,<br>
                <span class="greetings__pink-text"><?= htmlspecialchars($user->first_name) ?>!</span>
            </div>
        </h1>
        <p class="greetings__subtitle"><?=$subtitleText?></p>

        <p class="greetings__date"><?= strtoupper(date('l, F j, Y')) ?></p>
    </div>
</section>