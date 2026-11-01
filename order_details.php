<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = intval($_GET['order_id']);

$result = mysqli_query($conn,"
    SELECT p.name, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
");

if (!$result) {
    die(mysqli_error($conn));
}
?>

<h3 style="text-align:center;">Order ORD-<?= $order_id ?></h3>

<table border="1" width="80%" align="center">
<tr>
<th>Product</th>
<th>Qty</th>
<th>Amount</th>
</tr>

<?php
$total = 0;
while($row = mysqli_fetch_assoc($result)):
$sub = $row['quantity'] * $row['price'];
$total += $sub;
?>
<tr>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= $row['quantity'] ?></td>
<td>Rs.<?= number_format($sub,2) ?></td>
</tr>
<?php endwhile; ?>

<tr>
<td colspan="2"><b>Total</b></td>
<td><b>Rs.<?= number_format($total,2) ?></b></td>
</tr>
</table>

