<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/kim/public/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="home__wrapper">

    <section class="hero">

        <span class="hero__badge">
            Premium Wellness Platform
        </span>

        <h1 class="hero__title">
            Transform Your
            <span>Body & Mind</span>
        </h1>

        <p class="hero__description">
            Elite fitness training, strength conditioning and physiotherapy
            programs designed to help you reach your full potential.
        </p>

        <div class="hero__buttons">

            <a href="/kim/register"
               class="hero__button hero__button--primary">
                Join Now
            </a>

            <a href="#services"
               class="hero__button hero__button--secondary">
                Explore Services
            </a>

        </div>

    </section>

    <section id="services" class="services">

        <h2 class="services__title">
            Our Services
        </h2>

        <div class="services__grid">

            <article class="services__card">
                <h3>Fitness Training</h3>
                <p>
                    Personalized fitness programs.
                </p>
            </article>

            <article class="services__card">
                <h3>Strength Training</h3>
                <p>
                    Professional strength programs.
                </p>
            </article>

            <article class="services__card">
                <h3>Physiotherapy</h3>
                <p>
                    Recovery and rehabilitation sessions.
                </p>
            </article>

        </div>

    </section>

</div>

<?php require 'components/footer.php'; ?>

</body>
</html>
