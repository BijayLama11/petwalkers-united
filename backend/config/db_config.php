<?php
// Database configuration
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "petwalkers_db";
$db_port   = 8000;

// Connect to MySQL server
$conn = new mysqli($db_server, $db_user, $db_pass, "petwalkers_db", $db_port);

if($conn->connect_error){
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>