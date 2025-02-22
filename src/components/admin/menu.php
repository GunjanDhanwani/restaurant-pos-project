<?php
session_start();
include '../../config/database.php'; // Connect to database

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch menu items from database
$result = $conn->query("SELECT * FROM menu_items");

// Handle item deletion
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM menu_items WHERE id=$id");
    header("Location: menu.php"); // Refresh page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">🍽️ Manage Menu</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
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
