<?php

session_id("user");
session_start();
require "../../database.php";

if (isset($_POST['submit']) && !empty($_SESSION['userid'])) {

    $title = (string) $_POST['postTitle'];
    $link = (string) $_POST['postLink'];
    $text = (string) $_POST['postText'];
    $time = (string) $_POST['postTime'];

    echo $time;

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
    <title>Document</title>
</head>

<body>
    <form class="form" action="#" method="post">
        <input type="text" name="postTitle" placeholder="Title" />
        <input type="text" name="postLink" placeholder="Link" />
        <textarea type="text" name="postText" placeholder="Write here"></textarea>
        <input type="submit" name="submit" value="Post" />
        <input type="hidden" name="postTime" value="" />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
    </form>

</body>
<script>
    const getTime = () => {
        const today = new Date();
        const hours = today.getHours() - 12;
        const minutes =
            today.getMinutes() < 10 ? `0${today.getMinutes()}` : today.getMinutes();
        const meridiem = today.getHours() > 12 ? " PM" : " AM";
        const time = hours + ":" + minutes + meridiem;

        const form = document.getElementsByClassName("form")[0];
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