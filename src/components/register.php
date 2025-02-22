<?php
session_start();
include 'database.php'; // Connect to database

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // Hash password before storing
                $hashed_password = md5($password);

                // Insert new user (default role: customer)
                $role = "customer";
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Registration successful! Please login.";
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } else {
                $error = "Email is already registered!";
            }
        } else {
            $error = "Passwords do not match!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">📝 Register</h2>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <form method="POST" action="register.php">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name:</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

                <p class="mt-3 text-center">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
