<?php
session_id("user");
session_start();
require "../../database.php";

if (isset($_POST["editPost"])) {
    $postid = $_SESSION['currentPostID'];
    $posttext = $_POST["posttext"];

    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $stmt = $mysqli->prepare("update posts set description=? where id=?");


    $stmt->bind_param('si', $posttext, $postid);
    $stmt->execute();
    $stmt->close();

    header('Location: ../../post.php?id=' . $postid);
    exit;
}
