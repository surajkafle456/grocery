<?php
session_start();
include "db.php";

/* =========================
   SEARCH & CATEGORY QUERY
========================= */
$search = "";
$category = "";

// Base query
$sql = "SELECT * FROM products WHERE stock > 0 AND visible = 1";

// CATEGORY FILTER
if (isset($_GET['category']) && $_GET['category'] != "") {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $sql .= " AND category='$category'";
}

// SEARCH FILTER
if (isset($_GET['search']) && trim($_GET['search']) !== "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " AND name LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

// FETCH DISTINCT CATEGORIES
$cat_query = "SELECT DISTINCT category FROM products";
$cat_result = mysqli_query($conn, $cat_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>GroceryMart - Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo">
        <a href="home.php">🛒 GroceryMart</a>
    </div>

    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about_us.php">About Us</a></li>
    </ul>

    <!-- SEARCH -->
    <form method="get" class="search-box">
        <input type="text"
               name="search"
               placeholder="Search product..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- CART + USER -->
    <div class="nav-icons">
        <a href="cart.php" class="cart-link">
            <i class="fa fa-cart-shopping"></i>
            (<span id="cart-count">
                <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
            </span>)
        </a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="my_orders.php">My Orders</a>
        <?php endif; ?>

        <?php if(isset($_SESSION['username'])): ?>
            <a href="#"><?= htmlspecialchars($_SESSION['username']) ?></a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- ================= CATEGORIES ================= -->
<div class="categories">

    <!-- ALL BUTTON -->
    <a href="home.php" class="category-btn <?= ($category == "") ? 'active' : '' ?>">
        All
    </a>

    <?php while($cat = mysqli_fetch_assoc($cat_result)): ?>
        <a href="home.php?category=<?= urlencode($cat['category']) ?>"
           class="category-btn <?= ($category == $cat['category']) ? 'active' : '' ?>">
           
           <?= htmlspecialchars($cat['category']) ?>
        </a>
    <?php endwhile; ?>

</div>

<!-- ================= PRODUCTS ================= -->
<div class="products">

    <h2 style="text-align:center;">
        <?= $search
            ? 'Search Results for "'.htmlspecialchars($search).'"'
            : ($category ? htmlspecialchars($category)." Products" : "Available Products")
        ?>
    </h2>

<?php if(mysqli_num_rows($result) > 0): ?>
<div class="product-container">

<?php while($p = mysqli_fetch_assoc($result)): ?>
<div class="product-card">

    <img src="images/<?= htmlspecialchars($p['image']) ?>"
         alt="<?= htmlspecialchars($p['name']) ?>">

    <h3><?= htmlspecialchars($p['name']) ?></h3>

    <p>
        Price: Rs. <?= number_format($p['price'], 2) ?>
        <?= isset($p['unit']) ? '/ '.htmlspecialchars($p['unit']) : '' ?>
    </p>

    <p>
        Stock: <?= $p['stock'] ?>
        <?= isset($p['unit']) ? htmlspecialchars($p['unit']) : '' ?>
    </p>

    <!-- QUANTITY -->
    <div class="quantity-box">
        <button type="button" onclick="changeQty(<?= $p['id'] ?>, -1)">−</button>

        <input type="text"
               id="qty-<?= $p['id'] ?>"
               value="1"
               data-stock="<?= $p['stock'] ?>"
               inputmode="numeric"
               oninput="allowNumbers(this)"
               onblur="validateQty(this)">

        <button type="button" onclick="changeQty(<?= $p['id'] ?>, 1)">+</button>
    </div>

    <button type="button" onclick="addToCart(<?= $p['id'] ?>)">
        Add to Cart
    </button>

</div>
<?php endwhile; ?>

</div>
<?php else: ?>
<p style="text-align:center;">
    No products found<?= $search ? ' for "'.htmlspecialchars($search).'"' : '' ?>.
</p>
<?php endif; ?>

</div>

<!-- ================= FOOTER ================= -->
<div class="footer">
    <p>© 2026 GroceryMart | All rights reserved</p>
</div>

<!-- ================= JAVASCRIPT ================= -->
<script>

function allowNumbers(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

function changeQty(id, change) {
    const input = document.getElementById("qty-" + id);
    const stock = parseInt(input.dataset.stock);
    let value = parseInt(input.value) || 1;

    value += change;

    if (value < 1) value = 1;
    if (value > stock) {
        alert("Only " + stock + " items available");
        value = stock;
    }

    input.value = value;
}

function validateQty(input) {
    const stock = parseInt(input.dataset.stock);
    let value = parseInt(input.value);

    if (!value || value < 1) value = 1;
    if (value > stock) {
        alert("Only " + stock + " items available");
        value = stock;
    }

    input.value = value;
}

function addToCart(id) {
    const input = document.getElementById("qty-" + id);
    const qty = parseInt(input.value);
    const stock = parseInt(input.dataset.stock);

    if (!qty || qty < 1 || qty > stock) {
        alert("Invalid quantity");
        validateQty(input);
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status === 200) {
            const res = JSON.parse(this.responseText);
            if (res.success) {
                document.getElementById("cart-count").innerText = res.cartCount;
            } else {
                alert(res.message);
            }
        }
    };

    xhr.send("product_id=" + id + "&quantity=" + qty);
}

</script>

</body>
</html>