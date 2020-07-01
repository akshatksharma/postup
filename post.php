<?php
session_id("user");
session_start();
require "database.php";

$id =  $_GET["id"];
$_SESSION['currentPostID'] = $id;

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="home.php">Go back</a>
    <div class="post">
        <div class="post__content">
            <?php
            $edit = "<button class='edit--post'>Edit</button>";
            printf(
                "<h1 class='post__title'>%s</h1><div class='post__link'>%s</div><div class='post__user'>Posted by %s at %u</div><div class='post__text'>%s</div>%s",
                htmlentities($title),
                htmlentities($link),
                htmlentities($username),
                htmlentities($time),
                htmlentities($description),
                $edit
            )
            ?>aw
        </div>
        <div class="post__addcomment">
            <form action="addcomment.php" method="post">
                <textarea name="commentText" placeholder="Comment here"></textarea>
                <input type="submit" value="comment">
            </form>
        </div>
        <div class="comments">
            <h2>Comments</h2>
            <?php
            $stmt = $mysqli->prepare("select comment, username, id from comments where posts_id='{$id}'");
            if (!$stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->execute();
            $stmt->bind_result($comment, $username, $commentid);

            $edit = "<button class='edit'>Edit</button>";

            while ($stmt->fetch()) {
                printf(
                    "<div id=%u class='comment'><p class='comment__name'>%s</p><p class='comment__text'>%s</p>%s</div>",
                    htmlspecialchars($commentid),
                    htmlspecialchars($username),
                    htmlspecialchars($comment),
                    $edit
                );
            }
            ?>

        </div>
    </div>
</body>
<script>
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

    const commentEditButtons = document.getElementsByClassName("edit");

    for (let i = 0; i < commentEditButtons.length; i++) {
        const item = commentEditButtons[i];

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