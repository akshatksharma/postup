<?php

require "database.php";

$stmt = $mysqli->prepare("select username, title, link, id, time from posts");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
$stmt->bind_result($username, $title, $link, $id, $time);
?>

<div class="posts">
    <?php
    while ($stmt->fetch()) {
    ?>
        <div class="post">
            <form method="get" action='post.php'>
                <button type="submit" class="post__content">
                    <?php if ($link == "") { ?>
                        <a class="post__link" href="post.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title) ?></a>
                    <?php } else { ?>
                        <a class="post__link" href=<?php echo htmlspecialchars($link) ?>><?php echo htmlspecialchars($title) ?> </a>
                    <?php } ?>
                    <div class="post__user">posted by <?php echo htmlspecialchars($username) ?> at <?php echo htmlspecialchars($time) ?>
                    </div>
                </button>
                <input type='hidden' name='id' value='<?php echo $id; ?>' />
            </form>

        </div>



    <?php
    } ?>
</div>