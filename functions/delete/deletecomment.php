<?php
session_id("user");
session_start();
require "../../database.php";

$postid = $_SESSION['currentPostID'];
$commentid = (int) $_POST["id"];
$userid = (int) $_POST["userid"];

if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}

if ($_SESSION["userid"] == $userid) {
    $stmt = $mysqli->prepare("delete from comments where id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('i', $commentid);
    $stmt->execute();
    $stmt->close();

    header('Location: ../../post.php?id=' . $postid);
    exit;
}
