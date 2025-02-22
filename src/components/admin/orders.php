<?php
session_start();
include '../database.php';

// Get Year, Month, Day
$year = date("Y");
$month = date("m");
$day = date("d");

// Function to fetch sales safely
function fetch_sales($query) {
    $result = mysql_query($query);
    if (!$result) {
        return 0; // Return 0 if query fails
    }
    $data = mysql_fetch_assoc($result);
    return isset($data['sales']) ? $data['sales'] : 0;
}

// Fetch Yearly, Monthly, and Daily Sales
$yearly_sales = fetch_sales("SELECT SUM(total_price) AS sales FROM orders WHERE YEAR(order_date) = $year");
$monthly_sales = fetch_sales("SELECT SUM(total_price) AS sales FROM orders WHERE MONTH(order_date) = $month AND YEAR(order_date) = $year");
$daily_sales = fetch_sales("SELECT SUM(total_price) AS sales FROM orders WHERE DAY(order_date) = $day AND MONTH(order_date) = $month AND YEAR(order_date) = $year");

// Calculate 18% GST
$gst_yearly = $yearly_sales * 0.18;
$gst_monthly = $monthly_sales * 0.18;
$gst_daily = $daily_sales * 0.18;

// Fetch All Orders
$query_orders = "SELECT orders.order_id, users.name AS customer_name, orders.total_price, orders.status, orders.order_date FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.order_date DESC;";
$result_orders = mysql_query($query_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Admin Orders Management</h2>
        <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </div>

    <!-- Sales Reports -->
    <div class="row text-center my-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                <h4>Yearly Sales</h4>
                <p>$<?php echo number_format($yearly_sales, 2); ?></p>
                <small>GST (18%): $<?php echo number_format($gst_yearly, 2); ?></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                <h4>Monthly Sales</h4>
                <p>$<?php echo number_format($monthly_sales, 2); ?></p>
                <small>GST (18%): $<?php echo number_format($gst_monthly, 2); ?></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3">
                <h4>Daily Sales</h4>
                <p>$<?php echo number_format($daily_sales, 2); ?></p>
                <small>GST (18%): $<?php echo number_format($gst_daily, 2); ?></small>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm p-4">
        <h4>Recent Orders</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_orders && mysql_num_rows($result_orders) > 0) { ?>
                    <?php while ($order = mysql_fetch_assoc($result_orders)) { ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="4" class="text-center">No orders found</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
