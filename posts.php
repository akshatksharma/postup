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

<div class="posts flow">
    <?php
    while ($stmt->fetch()) {
    ?>
        <div class="post">

            <a class="post__link" href=//<?php echo htmlspecialchars($link) ?>><?php echo htmlspecialchars($title) ?> </a> <div class="post__userinfo">posted by <?php echo htmlspecialchars($username) ?> at <?php echo htmlspecialchars($time) ?></div>
        <form method="get" action='post.php'>
            <button type="submit" class="post__discussion">View</button>
            <input type='hidden' name='id' value='<?php echo $id; ?>' />
        </form>

</div>



<?php
    } ?>
</div>