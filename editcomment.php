<?php
session_id("user");
session_start();
require "database.php";

$postid = $_SESSION['currentPostID'];
$commentid = $_GET["id"];

echo $commentid;
$comment = $_POST["commenttext"];

$stmt = $mysqli->prepare("update comments set comment=? where id=?");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('si', $comment, $commentid);
$stmt->execute();
$stmt->close();

// header('Location: post.php?id=' . $postid);
exit;
