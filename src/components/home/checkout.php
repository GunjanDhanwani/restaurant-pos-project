
<?php
// session_start();
// include '../../config/database.php'; // Database connection
// // Check if cart is empty
// if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
//     header("Location: cart_page.php");
//     exit();
// }

// // Process order if form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Simulate order placement (In real applications, store order in database)
//     $_SESSION['order_success'] = "Your order has been placed successfully!";
//     unset($_SESSION['cart']); // Clear the cart
//     header("Location: order_confirmation.php");
//     exit();
// }

// $total_price = 0;

session_start();
include '../database.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: ../home/welcome.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = 0;

// Calculate total price
foreach ($_SESSION['cart'] as $id => $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Insert order
$query = "INSERT INTO orders (user_id, total_price, status) VALUES ($user_id, $total_price, 'pending')";
$result = mysql_query($query);

if (!$result) {
    die("Order creation failed: " . mysql_error());
}

// Get last inserted order ID
$order_id = mysql_insert_id();

// Insert each cart item into order_items table
foreach ($_SESSION['cart'] as $id => $item) {
    $product_name = mysql_real_escape_string($item['name']);
    $quantity = $item['quantity'];
    $price = $item['price'];

    $query = "INSERT INTO order_items (order_id, product_name, quantity, price) 
              VALUES ($order_id, '$product_name', $quantity, $price)";
    mysql_query($query);
}

// Clear cart after checkout
unset($_SESSION['cart']);

// Redirect to order confirmation
header("Location: order_confirmation.php?order_id=" . $order_id);
exit();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">🛒 Checkout</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_price += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="text-end">Total: $<?php echo number_format($total_price, 2); ?></h4>

        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address</label>
                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label for="payment" class="form-label">Payment Method</label>
                <select class="form-select" id="payment" name="payment" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                </select>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="cart_page.php" class="btn btn-secondary">⬅ Back to Cart</a>
                <button type="submit" class="btn btn-success">Place Order</button>
            </div>
        </form>
    </div>
</body>
</html>
