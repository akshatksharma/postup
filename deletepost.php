<?php
session_id("user");
session_start();
require "database.php";

$postid = $_SESSION['currentPostID'];
$formToken = $_POST['token'];
echo $formToken;

// printf("formtoken: %s, usertoken: %s", $formToken, $_SESSION['token']);

if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}

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
