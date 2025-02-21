<?php
$servername = "localhost";
$username = "rest_admin";  // WAMP default username
$password = "rest@123";      // WAMP default password
$dbname = "restaurant_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
