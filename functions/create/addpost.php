<?php

session_id("user");
session_start();
require "../../database.php";

if (isset($_POST['submit']) && !empty($_SESSION['userid'])) {

    $title = (string) $_POST['postTitle'];
    $link = (string) $_POST['postLink'];
    $text = (string) $_POST['postText'];
    $time = (string) $_POST['postTime'];

    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $stmt = $mysqli->prepare("insert into posts (username, userid, title, link, description, time) values (?,?,?,?,?,?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('sissss', $_SESSION['username'], $_SESSION['userid'], $title, $link, $text, $time);
    $stmt->execute();
    $stmt->close();

    header('Location: ../../home.php');
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add post</title>
    <link rel="stylesheet" href="https://use.typekit.net/jyw0vop.css" />
    <link rel="stylesheet" href="../../styles/resetstyles.css" />
    <link rel="stylesheet" href="../../styles/styles.css" />
    <link rel="stylesheet" href="../../styles/addpost.css" />
</head>

<body>
    <div class="authbar authbar--back">
        <a href="../../home.php">Go back</a>
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
    <div class="page page--addpost">

        <div class="page__title">Add a post</div>
        <form class="form form--addpost" action="#" method="post">
            <div class="form__inputs">
                <input type="text" name="postTitle" placeholder="Title *" required />
                <input type="text" name="postLink" placeholder="Link" />
                <textarea type="text" name="postText" placeholder="Write here"></textarea>
            </div>
            <input class="button" type="submit" name="submit" value="Post" />
            <input type="hidden" name="postTime" value="" />
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        </form>
    </div>


</body>
<script>
    const getTime = () => {
        const today = new Date();
        const hours = today.getHours() > 12 ? today.getHours() - 12 : today.getHours();
        const minutes =
            today.getMinutes() < 10 ? `0${today.getMinutes()}` : today.getMinutes();
        const meridiem = today.getHours() > 12 ? " PM" : " AM";
        const time = hours + ":" + minutes + meridiem;

        const form = document.getElementsByClassName("form--addpost")[0];
        const timeInputOld = document.getElementsByName("postTime")[0]

        const timeInput = document.createElement("input");
        timeInput.setAttribute('type', "hidden");
        timeInput.setAttribute('value', time);
        timeInput.setAttribute('name', "postTime");

        form.replaceChild(timeInput, timeInputOld);
    }
    getTime();
    setInterval(() => {
        getTime()
        console.log("updated time")
    }, 15000);
</script>

</html>