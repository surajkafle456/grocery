<?php
session_start();

/* Destroy admin session */
unset($_SESSION['admin']);
session_destroy();

/* Redirect to admin login */
header("Location: home.php");
exit;
