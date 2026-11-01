<?php
session_start();
include "db.php";

/* =====================
   LOGIN & DATA CHECK
===================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['delivery_details']) || empty($_SESSION['cart'])) {
    header("Location: home.php");
    exit;
}

$delivery = $_SESSION['delivery_details'];
$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Summary</title>
<style>
body{font-family:Segoe UI;background:#f4f6f8;}
.container{width:80%;margin:30px auto;}
.card{background:#fff;padding:20px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,.1);}
h2{color:#28a745;border-bottom:2px solid #28a745;padding-bottom:5px;}
table{width:100%;border-collapse:collapse;margin-top:15px;}
th,td{padding:10px;border:1px solid #ddd;text-align:center;}
th{background:#28a745;color:white;}
.total{font-size:18px;font-weight:bold;text-align:right;margin-top:15px;}
.btn{
    padding:12px 20px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    margin-top:20px;
}
</style>
</head>
<body>

<?php include "nav_bar.php"; ?>

<div class="container">

<div class="card">
<h2>Delivery Details</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($delivery['full_name']) ?></p>
<p><strong>Contact:</strong> <?= htmlspecialchars($delivery['contact']) ?></p>
<p><strong>City:</strong> <?= htmlspecialchars($delivery['city']) ?></p>
<p><strong>Municipality:</strong> <?= htmlspecialchars($delivery['municipality']) ?></p>
<p><strong>Ward:</strong> <?= htmlspecialchars($delivery['ward']) ?></p>
<p><strong>Address:</strong> <?= htmlspecialchars($delivery['address']) ?></p>
</div>

<br>

<div class="card">
<h2>Product Details</h2>

<table>
<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
</tr>

<?php
$total = 0;
foreach ($cart as $product_id => $qty) {

    $result = mysqli_query($conn, "SELECT name, price FROM products WHERE id=$product_id");
    $row = mysqli_fetch_assoc($result);

    $subtotal = $row['price'] * $qty;
    $total += $subtotal;
?>
<tr>
<td><?= htmlspecialchars($row['name']) ?></td>
<td>Rs. <?= number_format($row['price'],2) ?></td>
<td><?= $qty ?></td>
<td>Rs. <?= number_format($subtotal,2) ?></td>
</tr>
<?php } ?>
</table>

<div class="total">Total Amount: Rs. <?= number_format($total,2) ?></div>

<form method="post" action="confirm_order.php">
    <button class="btn">Confirm Order</button>
</form>

</div>

</div>

</body>
</html>
