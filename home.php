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
    $_SESSION['user'] = "test2";
    ?>
    <div>
        <button>Login</button>
        <button>Signup</button>
    </div>
    <div> <?php echo $_SESSION['user'] ?></div>
    <a href=" addpost.php">Submit</a>
    <div class="page">
        <?php include 'posts.php' ?>
    </div>


    <div class="modal">
        <div class="modal__content">
            <div class="modal__header">
                <span class="close">&times;</span>
                <h2>Signup</h2>
            </div>
            <div class="modal-body">
                <input type="text" placeholder="username" />
                <input type="password" placeholder="password" />
            </div>
        </div>

    </div>








</body>

</html>