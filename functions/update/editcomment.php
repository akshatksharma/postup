<?php
session_id("user");
session_start();
require "../../database.php";

$postid = $_SESSION['currentPostID'];
$commentid = (int) $_GET["id"];
$comment = (string) $_POST["commenttext"];

if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}


$stmt = $mysqli->prepare("update comments set comment=? where id=?");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('si', $comment, $commentid);
$stmt->execute();
$stmt->close();

header('Location: ../../post.php?id=' . $postid . "#comments");
exit;
