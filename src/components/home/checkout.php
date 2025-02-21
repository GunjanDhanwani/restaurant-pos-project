<?php
session_start();

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart_page.php");
    exit();
}

// Process order if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulate order placement (In real applications, store order in database)
    $_SESSION['order_success'] = "Your order has been placed successfully!";
    unset($_SESSION['cart']); // Clear the cart
    header("Location: order_confirmation.php");
    exit();
}

$total_price = 0;
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
