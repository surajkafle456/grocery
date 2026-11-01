<?php
session_start();
include "db.php";

/* =========================
   ADMIN LOGIN CHECK
========================= */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

/* =========================
   TOGGLE ORDER STATUS
========================= */
if (isset($_POST['toggle_status'])) {
    $order_id = intval($_POST['order_id']);

    $status_result = mysqli_query($conn, "SELECT status FROM orders WHERE id=$order_id");
    $row = mysqli_fetch_assoc($status_result);

    $new_status = ($row['status'] === 'Pending') ? 'Delivered' : 'Pending';
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id=$order_id");

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* =========================
   DELETE ENTIRE ORDER
========================= */
if (isset($_POST['delete_order'])) {
    $order_id = intval($_POST['order_id']);

    mysqli_query($conn, "DELETE FROM order_items WHERE order_id=$order_id");
    mysqli_query($conn, "DELETE FROM orders WHERE id=$order_id");

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* =========================
   FETCH ALL ORDER DATA
========================= */
$sql = "
SELECT
    o.id AS order_id,
    o.total_amount AS order_total_amount,
    o.order_date,
    o.status,

    oi.quantity,
    oi.price,
    p.name AS product_name,

    d.full_name,
    d.contact,
    d.city,
    d.municipality,
    d.ward,
    d.address

FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
LEFT JOIN products p ON oi.product_id = p.id
LEFT JOIN delivery_addresses d 
    ON d.user_id = o.user_id
    AND d.id = (
        SELECT id FROM delivery_addresses
        WHERE user_id = o.user_id
        ORDER BY created_at DESC
        LIMIT 1
    )
ORDER BY o.order_date DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Orders</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f2f2f2;
}
.container{
    width:95%;
    margin:30px auto;
}
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}
th, td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
}
th{
    background:#007bff;
    color:#fff;
}
.back{
    text-decoration:none;
    padding:8px 12px;
    background:#28a745;
    color:#fff;
    border-radius:4px;
}
.status-btn{
    padding:6px 10px;
    border:none;
    border-radius:4px;
    cursor:pointer;
    color:#fff;
}
.pending{ background:#ffc107; }
.delivered{ background:#28a745; }

.delete-btn{
    background:#dc3545;
    color:#fff;
    border:none;
    padding:6px 10px;
    cursor:pointer;
    border-radius:4px;
}
.delete-btn:hover{
    background:#c82333;
}
</style>
</head>

<body>

<div class="container">

<a href="admin_dashboard.php" class="back">⬅ Back</a>

<h2>All Orders / Receipts</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Item Total </th> <!-- ✅ FIXED -->
    <th>Order Total </th>
    <th>Date</th>
    <th>Status</th>
    <th>Delete</th>
</tr>

<?php if (mysqli_num_rows($result) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>

<?php
$item_total_amount = $row['quantity'] * $row['price'];
?>

<tr>
    <td><?= $row['order_id'] ?></td>

    <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($row['contact'] ?? 'N/A') ?></td>

    <td>
        <?= htmlspecialchars($row['city'] ?? '') ?>,
        <?= htmlspecialchars($row['municipality'] ?? '') ?>,
        Ward <?= htmlspecialchars($row['ward'] ?? '') ?><br>
        <?= htmlspecialchars($row['address'] ?? '') ?>
    </td>

    <td><?= htmlspecialchars($row['product_name'] ?? 'N/A') ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= number_format($row['price'], 2) ?></td>

    <!-- ✅ ITEM TOTAL AMOUNT -->
    <td><?= number_format($item_total_amount, 2) ?></td>

    <!-- ✅ ORDER TOTAL AMOUNT -->
    <td><?= number_format($row['order_total_amount'], 2) ?></td>

    <td><?= $row['order_date'] ?></td>

    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
            <button type="submit" name="toggle_status"
                class="status-btn <?= strtolower($row['status']) ?>">
                <?= $row['status'] ?>
            </button>
        </form>
    </td>

    <td>
        <?php if ($row['status'] === 'Delivered'): ?>
        <form method="POST" onsubmit="return confirm('Delete this order?');">
            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
            <button type="submit" name="delete_order" class="delete-btn">Delete</button>
        </form>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="12">No orders found</td>
</tr>
<?php endif; ?>

</table>

</div>
</body>
</html>



