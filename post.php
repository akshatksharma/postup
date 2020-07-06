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
$stmt2 = $mysqli->prepare("select id, userid, username, comment from comments where posts_id='{$id}' AND parentid is NULL");
if (!$stmt2) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt2->execute();
$stmt2->bind_result($commentid, $commentUserid, $commentUsername, $comment);

$result = $stmt2->get_result();
$stmt2->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title)  ?> </title>
    <link rel="stylesheet" href="https://use.typekit.net/jyw0vop.css" />
    <link rel="stylesheet" href="./styles/resetstyles.css" />
    <link rel="stylesheet" href="./styles/styles.css" />
    <link rel="stylesheet" href="./styles/post.css" />

</head>

<body>
    <div class="authbar authbar--back">
        <a href="./home.php">
            <=</a> <div class="userInfo">
                <p><?php echo empty($_SESSION['username']) ? "" :  $_SESSION['username'] ?></p>
                <?php if (empty($_SESSION['userid'])) { ?>
                    <button class="button button--login">Login</button>
                    <button class="button button--signup">Signup</button>
                <?php } else { ?>
                    <form class="form form--auth" action="./auth/logout.php">
                        <button class="button button--logout">Logout</button>
                    </form>

                <?php } ?>
    </div>
    </div>
    <div class="page">
        <div class="post flow">
            <div class="post__content flow">
                <div class="post__header">
                    <a class="post__title" href=//<?php echo htmlspecialchars($link) ?>><?php echo htmlspecialchars($title) ?> </a> <div class='post__user'>Posted by <?php echo htmlspecialchars($username); ?> at <?php echo htmlspecialchars($time) ?>
                </div>
            </div>
            <div class='post__text'> <?php echo htmlspecialchars($description); ?> </div>
            <?php if ($userid == $loggedUser) { ?>
                <div class="post__controls">
                    <button class="edit--post">Edit</button>
                    <form class="form form--edit" method="post" action="./functions/delete/deletepost.php?">
                        <button type="submit" class="delete--post">Delete</button>
                        <input type='hidden' name='userid' value='<?php echo "$userid"; ?>' />
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                    </form>
                </div>
            <?php }; ?>

        </div>

        <div class="post__addcomment">
            <?php if (!empty($_SESSION['userid'])) { ?>
                <form class="form form--addcomment" action="./functions/create/addcomment.php" method="post">
                    <textarea name="commentText" placeholder="Comment here"></textarea>
                    <input type="submit" value="comment">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                </form>
            <?php } else { ?>
                <textarea name="commentText" placeholder="Login to comment" readonly></textarea>
            <?php } ?>

        </div>

        <div class="comments flow">
            <h2>Comments</h2>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div id=<?php echo $row["id"]; ?> class='comment'>
                    <p class='comment__name'> <?php echo $row["username"]; ?> </p>
                    <p class='comment__text'> <?php echo $row["comment"]; ?> </p>
                    <?php if ($row["userid"] == $loggedUser) { ?>
                        <button class="edit--comment">Edit</button>
                        <form method="post" action="./functions/delete/deletecomment.php?">
                            <button type="submit" class="delete--comment">X</button>
                            <input type='hidden' name='id' value='<?php echo $row["id"]; ?>' />
                            <input type='hidden' name='userid' value='<?php echo $row["userid"]; ?>' />
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        </form>
                    <?php }; ?>
                    <div class="comment__reply">
                        <form method="post" action="./functions/create/replycomment.php">
                            <textarea name="commentText"></textarea>
                            <button type="submit" class="reply--comment">reply</button>
                            <input type='hidden' name='id' value='<?php echo $row["id"]; ?>' />
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        </form>
                    </div>
                    <div class="comment__replies">
                        <?php
                        $stmt3 = $mysqli->prepare("select id, userid, username, comment from comments where parentid='{$row["id"]}'");
                        if (!$stmt3) {
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmt3->execute();
                        $stmt3->bind_result($commentid, $commentUserid, $commentUsername, $comment);

                        while ($stmt3->fetch()) { ?>
                            <p class='comment__name'> <?php echo $commentUsername; ?> </p>
                            <p class='comment__text'> <?php echo $comment; ?> </p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>





            <!-- 
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
            <?php }; ?> -->
        </div>
    </div>

    </div>

</body>
<script>
    try {
        const postEditButton = document.getElementsByClassName("edit--post")[0];
        postEditButton.addEventListener('click', event => {
            const post = event.target.parentElement.parentElement;
            console.log(post);
            const postText = post.getElementsByClassName("post__text")[0];

            const form = document.createElement("FORM");
            form.setAttribute('method', "post");
            form.setAttribute('action', "./functions/update/editpost.php");

            const i = document.createElement("textarea"); //input element, text
            i.innerHTML = postText.innerHTML;
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