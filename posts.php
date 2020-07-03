<?php

require "database.php";

$stmt = $mysqli->prepare("select username, title, id, time from posts");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
$stmt->bind_result($username, $title, $id, $time);
?>
<div>Posts</div>
<?php
while ($stmt->fetch()) {
?>
    <div><a href='post.php?id=<?php echo $id ?>'><?php echo htmlspecialchars($title) ?> posted by <?php echo htmlspecialchars($username) ?> at <?php echo htmlspecialchars($time) ?></a></div>
<?php
} ?>