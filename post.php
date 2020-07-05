<?php
session_id("user");
session_start();
require "database.php";

$id =  $_GET["id"];
$_SESSION['currentPostID'] = $id;
$loggedUser = empty($_SESSION['userid']) ? "" :  $_SESSION['userid'];


// querying post table 
$stmt = $mysqli->prepare("select title, link, description, username, userid, time from posts where id=?");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($title, $link, $description, $username, $userid, $time);

while ($stmt->fetch()) {
}

// querying comment table 
$stmt2 = $mysqli->prepare("select id, userid, username, comment from comments where posts_id='{$id}'");
if (!$stmt2) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt2->execute();
$stmt2->bind_result($commentid, $commentUserid, $commentUsername, $comment);
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
                <?php if ($userid == $loggedUser) { ?>
                    <button class="edit--post">Edit</button>
                    <form method="post" action="./functions/delete/deletepost.php?">
                        <button type="submit" class="delete--post">X</button>
                        <input type='hidden' name='userid' value='<?php echo "$userid"; ?>' />
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                    </form>
                <?php }; ?>

            </div>

            <div class="post__addcomment">
                <?php if (!empty($_SESSION['userid'])) { ?>
                    <form action="./functions/create/addcomment.php" method="post">
                        <textarea name="commentText" placeholder="Comment here"></textarea>
                        <input type="submit" value="comment">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                    </form>
                <?php } else { ?>
                    <textarea name="commentText" placeholder="Login to comment" readonly></textarea>
                <?php } ?>

            </div>

            <div class="comments">
                <h2>Comments</h2>
                <?php while ($stmt2->fetch()) { ?>
                    <div id=<?php echo htmlspecialchars($commentid); ?> class='comment'>
                        <p class='comment__name'> <?php echo htmlspecialchars($commentUsername); ?> </p>
                        <p class='comment__text'> <?php echo htmlspecialchars($comment); ?> </p>
                        <?php if ($commentUserid == $loggedUser) { ?>
                            <button class="edit--comment">Edit</button>
                            <form method="post" action="./functions/delete/deletecomment.php?">
                                <button type="submit" class="delete--comment">X</button>
                                <input type='hidden' name='id' value='<?php echo "$commentid"; ?>' />
                                <input type='hidden' name='userid' value='<?php echo "$commentUserid"; ?>' />
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
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
            form.setAttribute('action', "./functions/update/editpost.php");

            const i = document.createElement("input"); //input element, text
            i.setAttribute('value', postText.innerHTML);
            i.setAttribute('type', "textarea");
            i.setAttribute('name', "posttext");

            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute("name", "editPost")
            s.setAttribute('value', "done");

            const h = document.createElement("input"); //input element, token button
            h.setAttribute('type', "hidden");
            h.setAttribute('name', "token");
            h.setAttribute('value', "<?php echo $_SESSION['token']; ?>");

            form.appendChild(i);
            form.appendChild(s);
            form.appendChild(h);

            post.replaceChild(form, postText);
        })
    } catch (error) {}

    const commentEditButtons = document.getElementsByClassName("edit--comment");

    for (let i = 0; i < commentEditButtons.length; i++) {
        const item = commentEditButtons[i];

        item.addEventListener('click', event => {
            console.log("hello");
            const comment = event.target.parentElement;
            const commentText = comment.getElementsByClassName("comment__text")[0];
            const commentid = comment.id;

            const form = document.createElement("FORM");
            form.setAttribute('method', "post");
            form.setAttribute('action', "./functions/update/editcomment.php?id=" + commentid);

            const i = document.createElement("input"); //input element, text
            i.setAttribute('value', commentText.innerHTML);
            i.setAttribute('type', "textarea");
            i.setAttribute('name', "commenttext");

            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute('value', "done");


            const h = document.createElement("input"); //input element, token button
            h.setAttribute('type', "hidden");
            h.setAttribute('name', "token");
            h.setAttribute('value', "<?php echo $_SESSION['token']; ?>");


            form.appendChild(i);
            form.appendChild(s);
            form.appendChild(h);

            commentText.remove();
            event.target.remove();
            comment.appendChild(form);
        })

    }
</script>

</html>