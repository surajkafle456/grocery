<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    mysqli_query($conn,"
        UPDATE orders SET status='$status' WHERE id=$order_id
    ");

    header("Location: admin_orders.php");
    exit;
}

$orders = mysqli_query($conn,"
    SELECT o.*, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
?>

<h2>All Orders</h2>

<?php while($o = mysqli_fetch_assoc($orders)): ?>

<hr>
<p><b>Order:</b> ORD-<?= $o['id'] ?></p>
<p><b>User:</b> <?= $o['username'] ?></p>
<p><b>Date:</b> <?= $o['order_date'] ?></p>
<p><b>Total:</b> Rs.<?= number_format($o['total_amount'],2) ?></p>
<p><b>Status:</b> <?= $o['status'] ?></p>

<form method="post">
<input type="hidden" name="order_id" value="<?= $o['id'] ?>">
<select name="status">
<option <?= $o['status']=='Pending'?'selected':'' ?>>Pending</option>
<option <?= $o['status']=='Approved'?'selected':'' ?>>Approved</option>
<option <?= $o['status']=='Delivered'?'selected':'' ?>>Delivered</option>
<option <?= $o['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
</select>
<button name="update_status">Update</button>
</form>

<?php endwhile; ?>
