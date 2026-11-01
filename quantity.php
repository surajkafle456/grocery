<?php
// quantity.php

// Make sure quantity is an array
if (!isset($_SESSION['quantity']) || !is_array($_SESSION['quantity'])) {
    $_SESSION['quantity'] = [];
}

// Only run when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {

    // Get product id
    $productId = (int) $_POST['product_id'];

    // Set default quantity
    if (!isset($_SESSION['quantity'][$productId])) {
        $_SESSION['quantity'][$productId] = 1;
    }

    // Increase quantity
    if (isset($_POST['increase'])) {
        $_SESSION['quantity'][$productId]++;
    }

    // Decrease quantity (minimum 1)
    if (isset($_POST['decrease']) && $_SESSION['quantity'][$productId] > 1) {
        $_SESSION['quantity'][$productId]--;
    }

    // Redirect to stop page refresh problem
    header("Location: home.php#product-" . $productId);
    exit;
}
?>

<!-- INSERT INTO products (name, price, image, stock) VALUES
('Wai Wai', 20, 'wai wai image.png', 100),
('Coca Cola', 70, 'coca cola.jpeg', 50),
('Sugar', 140, 'sugar.jpg', 40),
('Red Bull',90, 'Red bull.jpeg',50),
('Water',20, 'water.webp',100),
('Fanta',60,'fanta.webp',60),
('Kurkure', 50, 'kurkure.webp',70),
('lays', 50, 'lays.webp',30),
('Oreo',25,'oreo.webp',40),
('Kit Kat',50,'kit kat.jpg'); -->
