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
function show_children($parent, $level = 0)
{
    include "database.php";
    $id =  $_GET["id"];
    $sql = "select id, userid, username, comment from comments where posts_id='{$id}' AND parentid " . ($parent ? "= $parent" : "IS NULL");
    $result = $mysqli->query($sql);

    if (!$result) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $loggedUser = empty($_SESSION['userid']) ? "" :  $_SESSION['userid'];

    while ($row = $result->fetch_assoc()) { ?>

        <div id=<?php echo $row["id"]; ?> class='comment level<?php echo $level ?>'>
            <p class='comment__name'> <?php echo $row["username"]; ?> </p>
            <p class='comment__text'> <?php echo $row["comment"]; ?> </p>

            <?php if ($row["userid"] == $loggedUser) {
            ?>
                <button class="button button--tiny edit--comment">Edit</button>
                <form method="post" action="./functions/delete/deletecomment.php?">
                    <button type="submit" class="button button--tiny delete--comment">X</button>
                    <input type='hidden' name='id' value='<?php echo $row["id"]; ?>' />
                    <input type='hidden' name='userid' value='<?php echo $row["userid"]; ?>' />
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                </form>
            <?php }; ?>
            <?php
            if (!empty($_SESSION['userid'])) { ?>

                <form class="form form--reply" method="post" action="./functions/create/replycomment.php">
                    <button class="button button--tiny reply--comment">reply</button>
                    <input type='hidden' name='id' value='<?php echo $row["id"]; ?>' />
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                </form>
        </div>
<?php
            }
            show_children($row['id'], $level + 1);
        };
    }
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
            bacc</a>

        <div class="userInfo">
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
                    <a class="post__title" href=<?php echo htmlspecialchars($link) ?>><?php echo htmlspecialchars($title) ?> </a>
                    <div class='post__user'>Posted by <?php echo htmlspecialchars($username); ?> at <?php echo htmlspecialchars($time) ?>
                    </div>
                </div>
                <div class='post__text'> <?php echo htmlspecialchars($description); ?> </div>
                <?php if ($userid == $loggedUser) { ?>
                    <div class="post__controls">
                        <button class="button button--small edit--post">Edit</button>
                        <form class="form form--edit" method="post" action="./functions/delete/deletepost.php?">
                            <button class="button button--small" type="submit" class="delete--post">Delete</button>
                            <input type='hidden' name='userid' value='<?php echo "$userid"; ?>' />
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        </form>
                    </div>
                <?php }; ?>

                <div class="post__addcomment">
                    <?php if (!empty($_SESSION['userid'])) { ?>
                        <form class="form form--addcomment" action="./functions/create/addcomment.php" method="post">
                            <textarea name="commentText" placeholder="Comment here"></textarea>
                            <input class="button button--small" type="submit" value="Comment">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        </form>
                    <?php } else { ?>
                        <textarea name="commentText" placeholder="Login to comment" readonly></textarea>
                    <?php } ?>
                </div>
            </div>
            <div class="comments">
                <div class="flow">
                    <h2 id="comments">Comments</h2>
                    <?php show_children(0); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal--signup">
        <div class="modal__content">
            <div class="modal__header">
                <div class="close">
                    <svg class="close__button close__button--signup" viewBox=" 0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="_14dkERGUnSwisNWFcFX-0T">
                        <polygon fill="inherit" points="11.649 9.882 18.262 3.267 16.495 1.5 9.881 8.114 3.267 1.5 1.5 3.267 8.114 9.883 1.5 16.497 3.267 18.264 9.881 11.65 16.495 18.264 18.262 16.497"></polygon>
                    </svg>
                </div>
                <h2 class="header__title">Signup</h2>
            </div>
            <div class="modal__body">
                <form class="form" method="post" action="./auth/signup.php">
                    <div class="form__inputs">
                        <input type="text" name="username" placeholder="username" />
                        <input type="password" name="password" placeholder="password" />
                    </div>
                    <button class="button" type="submit">Signup</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal--login">
        <div class="modal__content">
            <div class="modal__header">
                <div class="close">
                    <svg class="close__button close__button--login" viewBox=" 0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="_14dkERGUnSwisNWFcFX-0T">
                        <polygon fill="inherit" points="11.649 9.882 18.262 3.267 16.495 1.5 9.881 8.114 3.267 1.5 1.5 3.267 8.114 9.883 1.5 16.497 3.267 18.264 9.881 11.65 16.495 18.264 18.262 16.497"></polygon>
                    </svg>
                </div>
                <h2 class="header__title">Login</h2>
            </div>
            <div class="modal__body">
                <form class="form" method="post" action="./auth/login.php">
                    <div class="form__inputs">
                        <input type="text" name="username" placeholder="username" />
                        <input type="password" name="password" placeholder="password" />
                    </div>
                    <button class="button" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>


<script>
    try {
        const postEditButton = document.getElementsByClassName("edit--post")[0];
        postEditButton.addEventListener('click', event => {
            const post = event.target.parentElement.parentElement;
            const postText = post.getElementsByClassName("post__text")[0];

            const form = document.createElement("FORM");
            form.classList.add("form");
            form.classList.add("form--edit");
            form.setAttribute('method', "post");
            form.setAttribute('action', "./functions/update/editpost.php");

            const i = document.createElement("textarea"); //input element, text
            i.innerHTML = postText.innerHTML.trim();
            i.setAttribute('name', "posttext");


            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute("name", "editPost")
            s.setAttribute('value', "done");
            s.classList.add("button");
            s.classList.add("button--small");



            const h = document.createElement("input"); //input element, token button
            h.setAttribute('type', "hidden");
            h.setAttribute('name', "token");
            <?php if (!empty($_SESSION['token'])) { ?>
                h.setAttribute('value', "<?php echo $_SESSION['token']; ?>");
            <?php } ?>

            form.appendChild(i);
            form.appendChild(s);
            form.appendChild(h);

            post.replaceChild(form, postText);
        })


    } catch (error) {}

    const commentEditButtons = document.getElementsByClassName("edit--comment");

    for (const item of commentEditButtons) {
        item.addEventListener('click', event => {
            const comment = event.target.parentElement;
            const commentText = comment.getElementsByClassName("comment__text")[0];
            const commentid = comment.id;

            try {
                const editButton = comment.getElementsByClassName("edit--comment")[0];
                const deleteButton = comment.getElementsByClassName("delete--comment")[0];
                const replyButton = comment.getElementsByClassName("reply--comment")[0];
                editButton.remove();
                deleteButton.remove();
                replyButton.remove();
            } catch (error) {}

            const form = document.createElement("FORM");
            form.classList.add("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "./functions/update/editcomment.php?id=" + commentid);

            const i = document.createElement("textarea");
            i.innerHTML = commentText.innerHTML.trim();

            i.setAttribute('type', "textarea");
            i.setAttribute('name', "commenttext");

            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute('value', "done");
            s.classList.add("button");
            s.classList.add("button--tiny");


            const h = document.createElement("input"); //input element, token button
            h.setAttribute('type', "hidden");
            h.setAttribute('name', "token");
            <?php if (!empty($_SESSION['token'])) { ?> h.setAttribute('value', "<?php echo $_SESSION['token']; ?>");
            <?php } ?>

            form.appendChild(i);
            form.appendChild(s);
            form.appendChild(h);
            comment.replaceChild(form, commentText);
        })
    };


    const commentReplyButtons = document.getElementsByClassName("reply--comment");


    for (const item of commentReplyButtons) {

        item.addEventListener('click', event => {
            event.preventDefault();
            const form = event.target.parentElement;
            form.style.display = "flex";

            const comment = form.parentElement;

            try {
                const editButton = comment.getElementsByClassName("edit--comment")[0];
                const deleteButton = comment.getElementsByClassName("delete--comment")[0];
                const replyButton = comment.getElementsByClassName("reply--comment")[0];
                editButton.remove();
                deleteButton.remove();
                replyButton.remove();
            } catch (error) {}

            const i = document.createElement("textarea"); //input element, text
            i.setAttribute('name', "commentText");
            i.setAttribute('placeholder', "Reply here");

            const d = document.createElement("div");
            d.classList.add("reply__controls")

            const s = document.createElement("input"); //input element, Submit button
            s.setAttribute('type', "submit");
            s.setAttribute('value', "done");
            s.classList.add("button");
            s.classList.add("button--tiny");

            const c = document.createElement("button");
            c.classList.add("button");
            c.classList.add("button--tiny");
            c.innerHTML = "cancel";

            c.onclick = (event) => {
                event.preventDefault();
                location.reload(true);
            }


            form.appendChild(i);
            d.appendChild(s);
            d.appendChild(c);
            form.appendChild(d);
        })
    }



    <?php if (empty($_SESSION['userid'])) { ?>

        const signupModal = document.getElementsByClassName("modal--signup")[0];
        const signupOpen = document.getElementsByClassName("button--signup")[0];
        const signupClose = document.getElementsByClassName("close__button--signup")[0];

        const loginModal = document.getElementsByClassName("modal--login")[0];
        const loginOpen = document.getElementsByClassName("button--login")[0];
        const loginClose = document.getElementsByClassName("close__button--login")[0];


        signupOpen.addEventListener('click', event => {
            signupModal.style.display = "block";
        })

        loginOpen.addEventListener('click', event => {
            loginModal.style.display = "block";
        })

        signupClose.addEventListener('click', event => {
            signupModal.style.display = "none";
        })

        loginClose.addEventListener('click', event => {
            loginModal.style.display = "none";
        })

        window.addEventListener('click', event => {
            if (event.target == signupModal) {
                signupModal.style.display = "none";
            }
        })

        window.addEventListener('click', event => {
            if (event.target == loginModal) {
                loginModal.style.display = "none";
            }
        })



    <?php } ?>
</script>

</html>