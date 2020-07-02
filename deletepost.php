<?php
session_id("user");
session_start();
require "database.php";

$postid = $_SESSION['currentPostID'];

$stmt = $mysqli->prepare("delete from comments where posts_id=?");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('i', $postid);
$stmt->execute();
$stmt->close();

$stmt = $mysqli->prepare("delete from posts where id=?");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('i', $postid);
$stmt->execute();
$stmt->close();


header('Location: home.php');
exit;
