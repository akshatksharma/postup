<?php
session_id("user");
session_start();
session_unset();
session_destroy();
header('Location: ../home.php');
