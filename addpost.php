<?php

session_id("user");
session_start();
require "database.php";



if (isset($_POST['submit'])) {
    $title = $_POST['postTitle'];
    $link = $_POST['postLink'];
    $text = $_POST['postText'];
    $time = time();

    $stmt = $mysqli->prepare("insert into posts (username, title, link, description, time) values (?,?,?,?,?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('ssssi', $_SESSION['user'], $title, $link, $text, $time);
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
    </form>

</body>

</html>