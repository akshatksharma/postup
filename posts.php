


<?php

require "database.php";

// $username = "test1";

$stmt = $mysqli->prepare("select username, title, id, time from posts");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$stmt->bind_result($username, $title, $id, $time);

echo "\n";
echo "<div>Posts</div>";
while ($stmt->fetch()) {
    printf(
        "\t<div><a href='post.php?id=%u'>%s posted by %s at %u</a></div>\n",
        htmlspecialchars($id),
        htmlspecialchars($title),
        htmlspecialchars($username),
        htmlspecialchars($time)
    );
}
echo "\n";
