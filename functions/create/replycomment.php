<?php
session_id("user");
session_start();
require "../../database.php";

$postid = $_SESSION['currentPostID'];
$commentText = (string) $_POST["commentText"];
$parentid = (int) $_POST["id"];

if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}

if (!empty($_SESSION['userid'])) {
    $stmt = $mysqli->prepare("insert into comments (userid, username, comment, posts_id, parentid) values (?,?,?,?,?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('issii', $_SESSION['userid'], $_SESSION['username'], $commentText, $postid, $parentid);

    $stmt->execute();
    $stmt->close();

    header('Location: ../../post.php?id=' . $postid);
    exit;
}
