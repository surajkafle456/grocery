<?php
session_start();
include "db.php";

$response = ["success" => false, "message" => "Invalid request"];

if (!isset($_POST['product_id'], $_POST['quantity'])) {
    echo json_encode($response);
    exit;
}

$product_id = (int) $_POST['product_id'];
$quantity   = (int) $_POST['quantity'];

/* ===== STRICT VALIDATION ===== */
if ($product_id <= 0 || $quantity <= 0) {
    $response['message'] = "Quantity must be a positive number";
    echo json_encode($response);
    exit;
}

/* ===== CHECK PRODUCT ===== */
$res = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id");

if (!$res || mysqli_num_rows($res) === 0) {
    $response['message'] = "Product not found";
    echo json_encode($response);
    exit;
}

$product = mysqli_fetch_assoc($res);

if ($quantity > $product['stock']) {
    $response['message'] = "Not enough stock available";
    echo json_encode($response);
    exit;
}

/* ===== ADD TO CART ===== */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = $quantity;
}

/* ===== SUCCESS ===== */
$response = [
    "success"   => true,
    "cartCount"=> count($_SESSION['cart'])
];

echo json_encode($response);







