<?php
session_start();

// Check if order was placed
$order_success = isset($_SESSION['order_success']) ? $_SESSION['order_success'] : null;
unset($_SESSION['order_success']); // Clear session message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <h2 class="text-success">✅ Order Confirmed!</h2>
        
        <?php if ($order_success): ?>
            <p class="lead"><?php echo htmlspecialchars($order_success); ?></p>
        <?php else: ?>
            <p class="text-danger">No order was placed. Please try again.</p>
        <?php endif; ?>

        <div class="mt-4">
            <a href="../welcome.php" class="btn btn-primary">🏠 Return to Home</a>
        </div>
    </div>
</body>
</html>
