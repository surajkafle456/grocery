<?php
session_start();
include "db.php";

// Fetch products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0): ?>
    <div class="product-container">
        <?php while($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="150">
                <h3><?= ucfirst($product['name']) ?></h3>
                <p>Price: Rs. <?= number_format($product['price'], 2) ?></p>

                <div class="quantity-box">
                    <button type="button" onclick="changeQty(<?= $product['id'] ?>, -1)">−</button>
                    <input type="number" 
                           id="qty-<?= $product['id'] ?>" 
                           value="<?= isset($_SESSION['cart'][$product['id']]) ? $_SESSION['cart'][$product['id']] : 1 ?>" 
                           min="1">
                    <button type="button" onclick="changeQty(<?= $product['id'] ?>, 1)">+</button>
                </div>

                <button type="button" class="action-btn" onclick="addToCart(<?= $product['id'] ?>)">
                    <i class="fa fa-cart-plus"></i> Add to Cart
                </button>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No products available.</p>
<?php endif; ?>


