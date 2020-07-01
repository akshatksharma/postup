<?php
session_id("user");
session_start();
require "database.php";

$postid = $_SESSION['currentPostID'];
$posttext = $_POST["posttext"];


$stmt = $mysqli->prepare("update posts set description=? where id=?");


$stmt->bind_param('si', $posttext, $postid);
$stmt->execute();
$stmt->close();

header('Location: post.php?id=' . $postid);
exit;
