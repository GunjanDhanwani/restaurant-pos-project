<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    
    if (isset($_POST['action'])) {
        if ($_POST['action'] == "increase") {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($_POST['action'] == "decrease") {
            if ($_SESSION['cart'][$id]['quantity'] > 1) {
                $_SESSION['cart'][$id]['quantity']--;
            } else {
                unset($_SESSION['cart'][$id]); // Remove item if quantity = 0
            }
        } elseif ($_POST['action'] == "remove") {
            unset($_SESSION['cart'][$id]); // Remove item from cart
        }
    }

    header("Location: cart_page.php");
    exit();
}

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">🛒 Shopping Cart</h2>
        
        <?php if (empty($cart)): ?>
            <div class="alert alert-warning text-center">Your cart is empty.</div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total_price += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button type="submit" name="action" value="decrease" class="btn btn-sm btn-danger">-</button>
                                <span class="mx-2"><?php echo $item['quantity']; ?></span>
                                <button type="submit" name="action" value="increase" class="btn btn-sm btn-success">+</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button type="submit" name="action" value="remove" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4 class="text-end">Total: $<?php echo number_format($total_price, 2); ?></h4>

            <div class="d-flex justify-content-between mt-4">
                <a href="../welcome.php" class="btn btn-primary">⬅ Continue Shopping</a>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
