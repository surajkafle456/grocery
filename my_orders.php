<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

/* Fetch all orders for the user */
$orders_result = mysqli_query($conn,"
    SELECT * FROM orders
    WHERE user_id = $user_id
    ORDER BY id DESC
");

if (!$orders_result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #28a745; color: white; }
        h2 { text-align: center; }
        .order-section { margin-bottom: 40px; }
        .order-title { background: #f0f0f0; padding: 10px; font-weight: bold; }

        .cart-actions {
    text-align: center;
    margin-top: 20px;
}

.cart-actions .action-btn {
    padding: 10px 22px;
    margin: 8px;
    background-color: #28a745;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 15px;
    font-weight: 500;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.cart-actions .action-btn i {
    font-size: 14px;
}

.cart-actions .action-btn:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

.cart-actions .action-btn:active {
    transform: scale(0.96);
}

    </style>
</head>
<body>

<?php include "nav_bar.php"; ?>

<h2>My Orders</h2>

<?php if(mysqli_num_rows($orders_result) > 0): ?>
    <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
        <div class="order-section">
            <div class="order-title">
                Order ID: ORD-<?= $order['id'] ?> | 
                Date: <?= $order['order_date'] ?> | 
                Status: <?= $order['status'] ?>
            </div>

            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>

                <?php
                $order_id = $order['id'];
                $items_result = mysqli_query($conn,"
                    SELECT p.name, oi.quantity, oi.price
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = $order_id
                ");

                $total = 0;
                while($item = mysqli_fetch_assoc($items_result)):
                    $subtotal = $item['quantity'] * $item['price'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>Rs.<?= number_format($item['price'],2) ?></td>
                        <td>Rs.<?= number_format($subtotal,2) ?></td>
                    </tr>
                <?php endwhile; ?>

                <tr>
                    <td colspan="3"><strong>Total Amount</strong></td>
                    <td><strong>Rs.<?= number_format($total,2) ?></strong></td>
                </tr>
            </table>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No orders found.</p>
<?php endif; ?>
<div >
<div >
<div class="cart-actions">
<a href="home.php" class="action-btn">
    <i class="fa fa-arrow-left"></i> Continue Shopping
</a>

</div>

</body>
</html>


