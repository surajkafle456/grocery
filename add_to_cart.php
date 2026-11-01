<?php
session_start();

if (!isset($_POST['id'])) {
    header("Location: home.php");
    exit;
}

$id  = (int)$_POST['id'];
$qty = (int)$_POST['qty'];

if ($qty <= 0) {
    $qty = 1;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] += $qty;
} else {
    $_SESSION['cart'][$id] = $qty;
}

/*
  🔥 THIS IS THE FIX
  Go back to the page user came from
*/
$back = $_SERVER['HTTP_REFERER'] ?? 'home.php';
header("Location: $back");
exit;

