<?php
session_id("user");
session_start();
session_destroy();
header('Location: home.php');
