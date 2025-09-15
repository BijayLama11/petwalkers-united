<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: /petwalkers-united/login.html");
exit;
?>