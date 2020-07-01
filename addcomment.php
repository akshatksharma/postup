<?php
require "database.php";
session_id("user");
session_start();

$commentText = $_POST["commentText"];
$postid = $_SESSION['currentPostID'];


$stmt = $mysqli->prepare("insert into comments (username, comment, posts_id) values (?,?,?)");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('ssi', $_SESSION['user'], $commentText, $postid);
$stmt->execute();
$stmt->close();

header('Location: post.php?id=' . $postid);
exit;
