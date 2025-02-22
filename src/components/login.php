<?php
session_start();
include 'database.php'; // Ensure correct DB connection

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysql_real_escape_string(trim($_POST['name'])); // Username
    $password = mysql_real_escape_string(trim($_POST['password']));
    
    // Encrypt password using md5 (⚠️ Not secure, but required for PHP 5.3.5)
    $hashed_password = md5($password);

    // Check if user exists
    $query = "SELECT * FROM users WHERE name = '$name' AND password = '$hashed_password' LIMIT 1";
    $result = mysql_query($query);

    if (!$result) {
        die("Query failed: " . mysql_error());
    }

    if (mysql_num_rows($result) == 1) {
        $user = mysql_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == "admin") {
            $_SESSION['admin_logged_in'] = true;
            header("Location: ../components/admin/dashboard.php");
        } else {
            header("Location: welcome.php");
        }
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h3 class="text-center">Login</h3>

                <!-- Display Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username (Name):</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="mt-3 text-center">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
