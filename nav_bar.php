<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart count
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<link rel="stylesheet" href="home.css">

<div class="navbar">
    <!-- Logo -->
    <div class="logo">
        <a href="home.php" style="text-decoration:none;color:black;">🛒 GroceryMart</a>
    </div>

    <!-- Main links -->
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about_us.php">About Us</a></li>
        <!-- <li><a href="contact.php">Contact</a></li> -->
    </ul>

    <!-- Right side: Cart + Login/Logout -->
    <div class="nav-icons">
        <a href="cart.php">
            <i class="fa fa-cart-shopping"></i>
            (<span id="cart-count"><?= $cartCount ?></span>)
        </a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="#"><?= htmlspecialchars($_SESSION['username']) ?></a>
            <a href="logout.php">🚪 Logout</a>
        <?php else: ?>
            <a href="login.php">🔐 Login</a>
        <?php endif; ?>
    </div>
</div>
<hr>

