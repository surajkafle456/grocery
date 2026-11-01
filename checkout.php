<?php
session_start();
include "db.php";

// Check if cart is not empty
if(empty($_SESSION['cart'])){
    echo "Your cart is empty!";
    exit;
}

// Reduce stock for each product in cart
foreach($_SESSION['cart'] as $id => $qty){
    // Optional: check current stock before updating
    $res = mysqli_query($conn, "SELECT stock FROM products WHERE id=$id");
    $product = mysqli_fetch_assoc($res);

    if($product['stock'] >= $qty){
        mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id=$id");
    } else {
        echo "Sorry, not enough stock for product ID $id";
        exit;
    }
}

// Clear the cart
$_SESSION['cart'] = [];

echo "Order placed successfully!";
?>
<a href="home.php">Back to Home</a>
