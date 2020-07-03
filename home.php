<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postup</title>
    <link rel="stylesheet" href="./styles/home.css" />
    <link rel="stylesheet" href="./styles/resetstyles.css" />
</head>

<body>
    <?php
    session_id("user");
    session_start();
    ?>
    <div>
        <?php if (empty($_SESSION['userid'])) { ?>
            <button class="button button--login">Login</button>
            <button class="button button--signup">Signup</button>
        <?php } else { ?>
            <form class="form" action="logout.php">
                <button class="button button--logout">Logout</button>
            </form>
        <?php } ?>
    </div>
    <?php echo empty($_SESSION['status']) ? "" : $_SESSION['status'] ?>

    <div> <?php echo empty($_SESSION['username']) ? "" :  $_SESSION['username'] ?></div>

    <div class="page">
        <?php if (!empty($_SESSION['userid'])) { ?>
            <a href=" addpost.php">Submit a Post</a>
        <?php } ?>
        <?php include 'posts.php' ?>
    </div>


    <div class="modal modal--signup">
        <div class="modal__content">
            <div class="modal__header">
                <span class="close close--signup">&times;</span>
                <h2>Signup</h2>
            </div>
            <div class="modal-body">
                <form method="post" action="signup.php">
                    <input type="text" name="username" placeholder="username" />
                    <input type="password" name="password" placeholder="password" />
                    <button type="submit">Signup</button>
                    <!-- <input type="password" placeholder="confirm password" /> -->
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal--login">
        <div class="modal__content">
            <div class="modal__header">
                <span class="close close--login">&times;</span>
                <h2>Login</h2>
            </div>
            <div class="modal-body">
                <form method="post" action="login.php">
                    <input type="text" name="username" placeholder="username" />
                    <input type="password" name="password" placeholder="password" />
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    const signupModal = document.getElementsByClassName("modal--signup")[0];
    const signupOpen = document.getElementsByClassName("button--signup")[0];
    const signupClose = document.getElementsByClassName("close--signup")[0];

    const loginModal = document.getElementsByClassName("modal--login")[0];
    const loginOpen = document.getElementsByClassName("button--login")[0];
    const loginClose = document.getElementsByClassName("close--login")[0];


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