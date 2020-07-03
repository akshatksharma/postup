<?php

session_id("user");
session_start();
require "database.php";



if (isset($_POST['submit']) && !empty($_SESSION['userid'])) {
    $title = $_POST['postTitle'];
    $link = $_POST['postLink'];
    $text = $_POST['postText'];
    $time = time();

    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $stmt = $mysqli->prepare("insert into posts (username, userid, title, link, description, time) values (?,?,?,?,?,?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('sisssi', $_SESSION['username'], $_SESSION['userid'], $title, $link, $text, $time);
    $stmt->execute();
    $stmt->close();

    header('Location: home.php');
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
    <form action="#" method="post">
        <input type="text" name="postTitle" placeholder="Title" />
        <input type="text" name="postLink" placeholder="Link" />
        <textarea type="text" name="postText" placeholder="Write here"></textarea>
        <input type="submit" name="submit" value="Post" />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
    </form>

</body>

</html>