<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KIM - Sign In</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="page__wrapper">

    <div class="form__container">
        <form action="/kim/login" method="POST" class="form__body">
            <!--  DACA am /login?error=' ' sa apara eroarea in form-->
            <?php if (isset($_GET['error'])):?>
                <div class="form__error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <!-- input-ul pentru mail -->
            <div class="form__group">
                <label class="form__label" for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="john.doe@kim.com">
            </div>

            <!-- input-ul pentru parola -->
            <div class="form__group">
                <label class="form__label" for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <!-- button submit -->
            <button type="submit" class="form__submit">Sign In</button>

            <!-- daca n-ai cont da register -->
            <p class="form__question">Don't have an account?
                <a href="/kim/register" class="form__hyperlink">Sign Up</a>
            </p>
        </form>

    </div>
</div>
</body>
</html>