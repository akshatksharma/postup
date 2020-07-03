<?php
session_id("user");
session_start();
require "database.php";

$username =  $_POST["username"];
$passwordGuess = $_POST["password"];

$stmt = $mysqli->prepare("SELECT password FROM users WHERE username=?");

$stmt->bind_param('s', $username);
$stmt->execute();

$stmt->bind_result($passwordHash);
$stmt->fetch();

if (password_verify($passwordGuess, $passwordHash)) {
    echo "successs";
    $_SESSION['user'] = $username;
};

header('Location: home.php');
exit;
