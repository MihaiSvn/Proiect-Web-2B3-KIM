<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/forms.css">
    <script src="/kim/public/js/auth_buttons_listeners/register.js?v=<?php echo time(); ?>" defer></script>

    <link rel="icon" href="/kim/public/images/serenity_icon.svg" type="image/svg+xml">


</head>
<body>
<div class="register__page__wrapper">

    <div class="form__side">


        <form id="registerForm" method="POST" class="form__body">

            <a href="/kim/home" class="register__brand__logo">
                <i class="fa-solid fa-heart"></i> Serenity
            </a>


            <!--  DACA am /register?error=' ' sa apara eroarea in form-->
                <div class="form__error" id="formError" style="display: none;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div id="errorMessage"></div>
                </div>


            <div class="form__welcome-banner">
                We're so excited to have you in our family. ✨ Your journey starts today.
            </div>

            <h1 class="form__title">Personal Information</h1>
            <p class="form__subtitle">Tell us a little about yourself to get started.</p>

            <!--  row pentru  nume&prenume    -->
            <div class="form__row">
                <div class="form__group">
                    <label class="form__label" for="form__firstname">First Name</label>
                    <input type="text" id="form__firstname" name="first_name" required placeholder="John">
                </div>

                <div class="form__group">
                    <label class="form__label" for="form__lastname">Last Name</label>
                    <input type="text" id="form__lastname" name="last_name" required placeholder="Doe">
                </div>
            </div>

            <!--  input mail -->
            <div class="form__group">
                <label class="form__label" for="form__email">Email</label>
                <input type="text" id="form__email" name="email" required
                       placeholder="gymgoerenjoyer@kim.com">
            </div>

            <!--   input parola   -->
            <div class="form__group">
                <label class="form__label" for="form__password">Password</label>
                <input type="password" id="form__password" name="password" required placeholder="••••••••">
            </div>

            <div class="form__group">
                <label class="form__label" for="form__confirm__password">Confirm Password</label>
                <input type="password" id="form__confirm__password" name="confirm_password" required placeholder="••••••••">
            </div>

            <button type="submit" class="form__submit" id="registerBtn">Create Account</button>

            <p class="form__question">Already have an account?
                <a href="/kim/login" class="form__hyperlink">Sign In</a>
            </p>
        </form>


    </div>
    <div class="image__side">
        <div class="image__overlay">
            <h2 class="image__title">Begin your wellness<br>journey</h2>
            <p class="image__subtitle">Join thousands on their path to a healthier life</p>
        </div>
    </div>
</div>

</body>
</html>
