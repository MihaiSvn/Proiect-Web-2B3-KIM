<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>
<body>
<div class="page__wrapper">

    <div class="form__container">
        <form action="/kim/register" method="POST" class="form__body">
            <!--  DACA am /register?error=' ' sa apara eroarea in form-->
            <?php if (isset($_GET['error'])):?>
                <div class="form__error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

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
                <input type="text" id="form__email" name="email" required placeholder="movieenthusiast123@gmail.com">
            </div>

            <!--   input parola   -->
            <div class="form__group">
                <label class="form__label" for="form__password">Password</label>
                <input type="password" id="form__password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="form__submit">Create Account</button>

            <p class="form__question">Already have an account?
                <a href="/kim/login" class="form__hyperlink">Sign In</a>
            </p>
        </form>

    </div>
</div>

</body>
</html>