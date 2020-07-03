<?php
require "database.php";
session_id("user");
session_start();

$commentText = $_POST["commentText"];
$postid = $_SESSION['currentPostID'];

if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}


$stmt = $mysqli->prepare("insert into comments (userid, username, comment, posts_id) values (?,?,?,?)");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
echo $_SESSION['username'];
$stmt->bind_param('issi', $_SESSION['userid'], $_SESSION['username'], $commentText, $postid);
$stmt->execute();
$stmt->close();

header('Location: post.php?id=' . $postid);
exit;
