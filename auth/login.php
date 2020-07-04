<?php
session_id("user");
session_start();
require "../database.php";

$username =  (string) $_POST["username"];
$passwordGuess = (string) $_POST["password"];

$stmt = $mysqli->prepare("SELECT password, id FROM users WHERE username=?");

if (!$stmt) {
    $_SESSION['status'] = "Incorrect username or password";
}

$stmt->bind_param('s', $username);
$stmt->execute();

$stmt->bind_result($passwordHash, $userid);
$stmt->fetch();

if (password_verify($passwordGuess, $passwordHash)) {
    $_SESSION['userid'] = $userid;
    $_SESSION['username'] = $username;
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['status'] = "";
} else {
    $_SESSION['status'] = "Incorrect username or password";
};

header('Location: ../home.php');
exit;
