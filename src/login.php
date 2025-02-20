<?php
session_start();

// Database connection
$servername = "localhost";
$username = "rest_admin"; // Default WAMP MySQL user
$password = "rest@123"; // Default WAMP MySQL password (empty)
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get login data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = md5($_POST['password']); // Hashing password

    // Using prepared statements for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $user;
        header("Location: welcome.php");
        exit();
    } else {
        echo "<script>alert('Invalid Username or Password'); window.location='index.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
