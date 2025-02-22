<?php
session_start();
include 'database.php'; // Ensure correct DB connection

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysql_real_escape_string(trim($_POST['name'])); // Username
    $password = mysql_real_escape_string(trim($_POST['password']));
    $confirm_password = mysql_real_escape_string(trim($_POST['confirm_password']));
    $role = mysql_real_escape_string(trim($_POST['role'])); // Role (admin/customer)

    // Validation: Ensure fields are not empty
    if (empty($name) || empty($password) || empty($confirm_password) || empty($role)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Encrypt password using md5 (⚠️ Not secure, but required for PHP 5.3.5)
        $hashed_password = md5($password);

        // Check if username already exists
        $check_query = "SELECT * FROM users WHERE name = '$name' LIMIT 1";
        $check_result = mysql_query($check_query);

        if (!$check_result) {
            die("Query failed: " . mysql_error());
        }

        if (mysql_num_rows($check_result) > 0) {
            $error = "Username already taken!";
        } else {
            // Insert new user into the database
            $query = "INSERT INTO users (name, password, role) VALUES ('$name', '$hashed_password', '$role')";
            $result = mysql_query($query);

            if ($result) {
                $success = "Registration successful! <a href='login.php' class='alert-link'>Login here</a>";
            } else {
                $error = "Registration failed: " . mysql_error();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h3 class="text-center">Register</h3>

                <!-- Display Error or Success Message -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username (Name):</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password:</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role:</label>
                        <select name="role" class="form-select" required>
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

                <p class="mt-3 text-center">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
