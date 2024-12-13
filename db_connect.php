<?php
// Database configuration
$servername = "db";
$username = "e-commerce";
$password = "e-commerce123";
$dbname = "e-commerce";

// Establish connection
$connect = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Return the connection
return $connect;
?>
