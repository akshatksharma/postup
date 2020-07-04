<?php

session_id("user");
session_start();
require "../database.php";

$username =  $_POST["username"];
$passwordRaw = $_POST["password"];

$passwordSH = password_hash($passwordRaw, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("insert into users (username, password) values (?,?)");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('ss', $username, $passwordSH);
$stmt->execute();
$stmt->close();

header('Location: ../home.php');
exit;
