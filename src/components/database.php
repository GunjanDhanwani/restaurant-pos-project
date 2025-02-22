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

$conn2 = mysql_connect($servername, $username, $password);
if (!$conn2) {
    die("Database connection failed: " . mysql_error());
}

// Select database
$db_selected2 = mysql_select_db($dbname, $conn2);
if (!$db_selected2) {
    die("Database selection failed: " . mysql_error());
}

?>
