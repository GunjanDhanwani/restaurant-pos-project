<?php
session_start();
include '../database.php';

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    echo "Invalid Order ID.";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$query = "SELECT orders.order_id, users.name AS customer_name, orders.total_price, orders.status FROM orders JOIN users ON orders.user_id = users.id WHERE orders.order_id = ?";
$stmt = mysql_query(sprintf("SELECT orders.order_id, users.name AS customer_name, orders.total_price, orders.status FROM orders JOIN users ON orders.user_id = users.id WHERE orders.order_id = %d", $order_id));

if (!$stmt || mysql_num_rows($stmt) == 0) {
    echo "Order not found.";
    exit();
}

$order = mysql_fetch_assoc($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h2 class="text-center">Order Confirmation</h2>
        <hr>
        <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
        <p><strong>Status:</strong> <span class="badge bg-<?php echo ($order['status'] == 'Success') ? 'success' : 'warning'; ?>">
            <?php echo $order['status']; ?>
        </span></p>

        <?php if ($order['status'] == 'pending') { ?>
            <form method="post" class="mt-4">
                <h4>Enter Payment Details</h4>
                <div class="mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" required>
                </div>
                <div class="mb-3">
                    <label for="expiry" class="form-label">Expiry Date</label>
                    <input type="text" class="form-control" id="expiry" name="expiry" placeholder="MM/YY" required>
                </div>
                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="password" class="form-control" id="cvv" name="cvv" required>
                </div>
                <button type="submit" name="pay" class="btn btn-primary w-100">Pay Now</button>
            </form>
        <?php } else { ?>
            <div class="alert alert-success text-center mt-3">Payment Completed. Your Order is Confirmed!</div>
            <a href="../welcome.php" class="btn btn-success w-100">Go to Home</a>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Process payment
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Here you can add actual payment processing logic
    // For now, we simulate successful payment by updating order status

    $update_query = sprintf("UPDATE orders SET status = 'Success' WHERE order_id = %d", $order_id);
    mysql_query($update_query);

    // Redirect to avoid resubmission
    header("Location: order_confirmation.php?order_id=$order_id");
    exit();
}
?>
