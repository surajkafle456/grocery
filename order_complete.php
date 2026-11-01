<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    die("Order Failed: Login required");
}

if (empty($_SESSION['cart'])) {
    die("Order Failed: Cart empty");
}

$user_id = intval($_SESSION['user_id']);
$cart = $_SESSION['cart'];

$total_amount = 0;

/* calculate total */
foreach ($cart as $pid => $qty) {
    $pid = intval($pid);
    $qty = intval($qty);

    $q = mysqli_query($conn,"SELECT price FROM products WHERE id=$pid");
    $row = mysqli_fetch_assoc($q);
    if ($row) {
        $total_amount += $row['price'] * $qty;
    }
}

/* insert order */
$sql = "
INSERT INTO orders (user_id, total_amount, order_date, status)
VALUES ($user_id, $total_amount, NOW(), 'Pending')
";

if (!mysqli_query($conn, $sql)) {
    die("Order Failed: " . mysqli_error($conn));
}

$order_id = mysqli_insert_id($conn);

/* insert order items */
foreach ($cart as $pid => $qty) {
    $pid = intval($pid);
    $qty = intval($qty);

    $q = mysqli_query($conn,"SELECT price FROM products WHERE id=$pid");
    $row = mysqli_fetch_assoc($q);
    if (!$row) continue;

    mysqli_query($conn,"
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES ($order_id, $pid, $qty, {$row['price']})
    ");
}

unset($_SESSION['cart']);

header("Location: my_orders.php");
exit;
