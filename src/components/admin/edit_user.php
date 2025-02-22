<?php
session_start();
include '../database.php'; // Include database connection

// Check if user ID is set
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$user_id = (int) $_GET['id']; // Sanitize user ID

// Fetch user details
$query = "SELECT id, name, email, role FROM users WHERE id = $user_id";
$result = mysql_query($query);

if (!$result || mysql_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysql_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysql_real_escape_string($_POST['name']);
    $email = mysql_real_escape_string($_POST['email']);
    $role = mysql_real_escape_string($_POST['role']);

    $update_query = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE id = $user_id";
    
    if (mysql_query($update_query)) {
        header("Location: manage_users.php?success=User updated successfully");
        exit();
    } else {
        echo "<p class='alert alert-danger'>Update failed: " . mysql_error() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">Edit User</h2>

    <div class="card shadow-sm p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="customer" <?php echo ($user['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update User</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
