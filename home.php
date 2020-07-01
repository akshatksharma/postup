<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="addpost.php">Submit</a>
    <?php

    session_id("user");
    session_start();
    $_SESSION['user'] = "test1";



    include 'posts.php' ?>

</body>

</html>