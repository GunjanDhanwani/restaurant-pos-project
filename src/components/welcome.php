<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit();
}
include "database.php";

$query = "SELECT * FROM menu";
$result = $conn->query($query);

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant POS - Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .card { height: 100%; display: flex; flex-direction: column; }
        .card img { height: 200px; object-fit: cover; }
        .card-body { flex-grow: 1; display: flex; flex-direction: column; }
        .price { font-weight: bold; color: #ff5722; }
        .quantity-controls { display: flex; align-items: center; justify-content: center; }
        .quantity-controls button { width: 30px; height: 30px; background: #007bff; color: white; border: none; cursor: pointer; }
        .quantity-controls input { width: 40px; text-align: center; font-size: 16px; margin: 0 5px; border: 1px solid #ddd; }
        .cart-icon { position: relative; }
        .cart-count { position: absolute; top: -5px; right: -5px; background: red; color: white; font-size: 12px; padding: 3px 7px; border-radius: 50%; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurant POS</a>
            <div class="d-flex">
                <a href="../components/home/cart_page.php" class="btn btn-warning cart-icon">
                    🛒 Cart <span class="cart-count"><?php echo $cart_count; ?></span>
                </a>
                <a href="logout.php" class="btn btn-danger ms-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Restaurant Menu</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card mb-3">
                        <img src="../../assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="Menu Item">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <div class="quantity-controls">
                                <button class="btn-decrease" data-id="<?php echo $row['id']; ?>">-</button>
                                <input type="text" id="quantity-<?php echo $row['id']; ?>" value="1" readonly>
                                <button class="btn-increase" data-id="<?php echo $row['id']; ?>">+</button>
                            </div>
                            <button class="btn btn-primary mt-2 add-to-cart" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-price="<?php echo $row['price']; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $(".btn-increase").click(function () {
                let id = $(this).data("id");
                let quantity = $("#quantity-" + id).val();
                $("#quantity-" + id).val(parseInt(quantity) + 1);
            });

            $(".btn-decrease").click(function () {
                let id = $(this).data("id");
                let quantity = $("#quantity-" + id).val();
                if (quantity > 1) {
                    $("#quantity-" + id).val(parseInt(quantity) - 1);
                }
            });

            $(".add-to-cart").click(function () {
                let id = $(this).data("id");
                let name = $(this).data("name");
                let price = $(this).data("price");
                let quantity = $("#quantity-" + id).val();

                $.post("../components/home/cart.php", { id: id, name: name, price: price, quantity: quantity }, function (response) {
                    alert(response);
                    location.reload(); // Refresh cart count
                });
            });
        });
    </script>
</body>
</html>