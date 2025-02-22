<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php"); // Redirect if not logged in as admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">📊 Admin Dashboard</h2>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">📜 Orders</h5>
                        <p class="card-text">View and manage customer orders</p>
                        <a href="orders.php" class="btn btn-primary">View Orders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">🍽️ Menu</h5>
                        <p class="card-text">Manage restaurant menu</p>
                        <a href="menu.php" class="btn btn-warning">Manage Menu</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">👥 Users</h5>
                        <p class="card-text">Manage customers & admins</p>
                        <a href="manage_users.php" class="btn btn-success">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
