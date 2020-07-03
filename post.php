<?php
session_id("user");
session_start();
require "database.php";

$id =  $_GET["id"];
$_SESSION['currentPostID'] = $id;
$loggedUser = $_SESSION['user'];

$stmt = $mysqli->prepare("select title, link, description, username, time from posts where id=?");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($title, $link, $description, $username, $time);

while ($stmt->fetch()) {
}


$stmt2 = $mysqli->prepare("select comment, username, id from comments where posts_id='{$id}'");
if (!$stmt2) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt2->execute();
$stmt2->bind_result($comment, $commentUsername, $commentid);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title)  ?> </title>
    <link rel="stylesheet" href="./styles/resetstyles.css" />

</head>

<body>
    <div class="page">
        <a href="home.php">Go back</a>
        <div class="post">
            <div class="post__content">
                <h1 class='post__title'> <?php echo htmlspecialchars($title); ?> </h1>
                <div class='post__link'> <?php echo htmlspecialchars($link); ?> </div>
                <div class='post__user'>Posted by <?php echo htmlspecialchars($username); ?> at <?php echo htmlspecialchars($time) ?> </div>
                <div class='post__text'> <?php echo htmlspecialchars($description); ?> </div>
                <?php if ($username == $loggedUser) { ?>
                    <button class="edit--post">Edit</button>
                    <form method="GET" action="deletepost.php?">
                        <button type="submit" class="delete--post">X</button>
                    </form>

                <?php }; ?>

            </div>

            <div class="post__addcomment">
                <form action="addcomment.php" method="post">
                    <textarea name="commentText" placeholder="Comment here"></textarea>
                    <input type="submit" value="comment">
                </form>
            </div>

            <div class="comments">
                <h2>Comments</h2>
                <?php while ($stmt2->fetch()) { ?>
                    <div id=<?php echo htmlspecialchars($commentid); ?> class='comment'>
                        <p class='comment__name'> <?php echo htmlspecialchars($commentUsername); ?> </p>
                        <p class='comment__text'> <?php echo htmlspecialchars($comment); ?> </p>
                        <?php if ($commentUsername == $loggedUser) { ?>
                            <button class="edit--comment">Edit</button>
                            <form method="GET" action="deletecomment.php?">
                                <button type="submit" class="delete--comment">X</button>
                                <input type='hidden' name='id' value='<?php echo "$commentid"; ?>' />
                            </form>
                        <?php }; ?>
                    </div>
                <?php }; ?>
            </div>
        </div>

    </div>

</body>
<script>
    try {
        const postEditButton = document.getElementsByClassName("edit--post")[0];

        postEditButton.addEventListener('click', event => {
            const post = event.target.parentElement;
            const postText = post.getElementsByClassName("post__text")[0];

            const form = document.createElement("FORM");
            form.setAttribute('method', "post");
            form.setAttribute('action', "editpost.php");

            const i = document.createElement("input"); //input element, text
            i.setAttribute('value', postText.innerHTML);
            i.setAttribute('type', "textarea");
            i.setAttribute('name', "posttext");

            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute('value', "done");

            form.appendChild(i);
            form.appendChild(s);

            post.replaceChild(form, postText);
        })
    } catch (error) {}

    const commentEditButtons = document.getElementsByClassName("edit--comment");

    for (let i = 0; i < commentEditButtons.length; i++) {
        const item = commentEditButtons[i];
        console.log("helll");

        item.addEventListener('click', event => {
            const comment = event.target.parentElement;
            const commentText = comment.getElementsByClassName("comment__text")[0];
            const commentid = comment.id;

            const form = document.createElement("FORM");
            form.setAttribute('method', "post");
            form.setAttribute('action', "editcomment.php?id=" + commentid);

            const i = document.createElement("input"); //input element, text
            i.setAttribute('value', commentText.innerHTML);
            i.setAttribute('type', "textarea");
            i.setAttribute('name', "commenttext");

            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute('value', "done");

            commentText.remove();
            event.target.remove();
            form.appendChild(i);
            form.appendChild(s);
            comment.appendChild(form);
        })

    }
</script>

</html>