<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
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


    $stmt = $mysqli->prepare("select comment, username, id from comments where posts_id='{$id}'");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($comment, $username, $commentid);

    $isLoggedUser = $username == $loggedUser;

    ?>



    <a href="home.php">Go back</a>
    <div class="post">
        <div class="post__content">
            <h1 class='post__title'> <?php echo htmlspecialchars($title); ?> </h1>
            <div class='post__link'> <?php echo htmlspecialchars($link); ?> </div>
            <div class='post__user'>Posted by <?php echo htmlspecialchars($username); ?> at <?php echo htmlspecialchars($time) ?> </div>
            <div class='post__text'> <?php echo htmlspecialchars($description); ?> </div>
        </div>
`
        <div class="post__addcomment">
            <form action="addcomment.php" method="post">
                <textarea name="commentText" placeholder="Comment here"></textarea>
                <input type="submit" value="comment">
            </form>
        </div>

        <div class="comments">
            <h2>Comments</h2>
            <?php while ($stmt->fetch()) { ?>
                <div id=<?php echo htmlspecialchars($commentid); ?> class='comment'>
                    <p class='comment__name'> <?php echo htmlspecialchars($username); ?> </p>
                    <p class='comment__text'> <?php echo htmlspecialchars($comment); ?> </p>
                    <?php if ($isLoggedUser) { ?>
                        <button class="edit--button">Edit</button>
                    <?php }; ?>
                </div>
            <?php }; ?>
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

    const commentEditButtons = document.getElementsByClassName("edit--comment");

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