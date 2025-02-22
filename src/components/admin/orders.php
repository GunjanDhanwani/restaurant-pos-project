<?php
session_start();
include '../database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch orders from database
$result = $conn->query("SELECT * FROM orders");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">📜 Customer Orders</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
