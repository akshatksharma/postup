<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postup</title>
    <link rel="stylesheet" href="https://use.typekit.net/jyw0vop.css" />
    <link rel="stylesheet" href="./styles/resetstyles.css" />
    <link rel="stylesheet" href="./styles/styles.css" />
    <link rel="stylesheet" href="./styles/home.css" />
</head>

<body>
    <?php
    session_id("user");
    session_start();
    ?>
    <div class="authbar">
        <a href="../../home.php"></a>
        <div class="userInfo">
            <p><?php echo empty($_SESSION['username']) ? "" :  $_SESSION['username'] ?></p>
            <?php if (empty($_SESSION['userid'])) { ?>
                <button class="button button--login">Login</button>
                <button class="button button--signup">Signup</button>
            <?php } else { ?>
                <form class="form form--auth" action="../../auth/logout.php">
                    <button class="button button--logout">Logout</button>
                </form>

            <?php } ?>
        </div>

    </div>
    <div class="page">
        <?php echo empty($_SESSION['status']) ? null : $_SESSION['status'] ?>
        <div class="page__header">
            <div class="page__title">Posts</div>
            <?php if (!empty($_SESSION['userid'])) { ?>
                <form class="form" action="./functions/create/addpost.php">
                    <button class="button">Submit a Post</button>
                </form>
            <?php } ?>
        </div>
        <?php include 'posts.php' ?>

    </div>


    <!-- sliding modals from w3schools.com -->


    <div class="modal modal--signup">
        <div class="modal__content">
            <div class="modal__header">
                <div class="close">
                    <svg class="close__button close__button--signup" viewBox=" 0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="_14dkERGUnSwisNWFcFX-0T">
                        <polygon fill="inherit" points="11.649 9.882 18.262 3.267 16.495 1.5 9.881 8.114 3.267 1.5 1.5 3.267 8.114 9.883 1.5 16.497 3.267 18.264 9.881 11.65 16.495 18.264 18.262 16.497"></polygon>
                    </svg>
                </div>
                <h2 class="header__title">Signup</h2>
            </div>
            <div class="modal__body">
                <form class="form" method="post" action="./auth/signup.php">
                    <div class="form__inputs">
                        <input type="text" name="username" placeholder="username" />
                        <input type="password" name="password" placeholder="password" />
                    </div>
                    <button class="button" type="submit">Signup</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal--login">
        <div class="modal__content">
            <div class="modal__header">
                <div class="close">
                    <svg class="close__button close__button--login" viewBox=" 0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="_14dkERGUnSwisNWFcFX-0T">
                        <polygon fill="inherit" points="11.649 9.882 18.262 3.267 16.495 1.5 9.881 8.114 3.267 1.5 1.5 3.267 8.114 9.883 1.5 16.497 3.267 18.264 9.881 11.65 16.495 18.264 18.262 16.497"></polygon>
                    </svg>
                </div>
                <h2 class="header__title">Login</h2>
            </div>
            <div class="modal__body">
                <form class="form" method="post" action="./auth/login.php">
                    <div class="form__inputs">
                        <input type="text" name="username" placeholder="username" />
                        <input type="password" name="password" placeholder="password" />
                    </div>
                    <button class="button" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    const signupModal = document.getElementsByClassName("modal--signup")[0];
    const signupOpen = document.getElementsByClassName("button--signup")[0];
    const signupClose = document.getElementsByClassName("close__button--signup")[0];

    const loginModal = document.getElementsByClassName("modal--login")[0];
    const loginOpen = document.getElementsByClassName("button--login")[0];
    const loginClose = document.getElementsByClassName("close__button--login")[0];


    signupOpen.addEventListener('click', event => {
        signupModal.style.display = "block";
    })

    loginOpen.addEventListener('click', event => {
        loginModal.style.display = "block";
    })

    signupClose.addEventListener('click', event => {
        signupModal.style.display = "none";
    })

    loginClose.addEventListener('click', event => {
        loginModal.style.display = "none";
    })

    window.addEventListener('click', event => {
        if (event.target == signupModal) {
            signupModal.style.display = "none";
        }
    })

    window.addEventListener('click', event => {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    })
</script>

</html>