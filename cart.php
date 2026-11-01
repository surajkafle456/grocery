<?php
session_start();
include "db.php";

/* ===== LOGIN CHECK ===== */
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'cart.php';
    header("Location: login.php");
    exit;
}

/* ===== CART CHECK ===== */
if (empty($_SESSION['cart'])) {
    header("Location: home.php"); 
    exit;
}

/* ===== UPDATE QUANTITY ===== */
if (isset($_POST['update_qty'])) {
    $pid = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']);

    if ($qty <= 0) {
        unset($_SESSION['cart'][$pid]);
    } else {
        $_SESSION['cart'][$pid] = $qty;
    }

    header("Location: cart.php");
    exit;
}

/* ===== DELETE PRODUCT ===== */
if (isset($_POST['delete_product'])) {
    $pid = intval($_POST['product_id']);
    unset($_SESSION['cart'][$pid]);
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart & Payment</title>
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            max-width: 1400px;
            margin: 50px auto; /* centers horizontally and adds top margin */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .cart-container h2 {
            text-align: center;
            color: #28a745;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #28a745;
            color: white;
        }

        .cart-actions {
            text-align: center;
        }

        .action-btn {
            padding: 8px 20px;
            margin: 5px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .action-btn.delete {
            background: #dc3545;
        }

        .qty-input {
            width: 50px;
            text-align: center;
        }

        form {
            display: inline-block;
        }

        p {
            text-align: center;
            font-size: 16px;
        }

        @media screen and (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }
            .cart-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<?php include "nav_bar.php"; ?>
<div class="cart-container">
    <h2><i class="fa fa-shopping-cart"></i> Your Cart</h2>

    <?php if(!empty($_SESSION['cart'])): ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php 
            $grandTotal = 0;
            foreach($_SESSION['cart'] as $id => $qty):
                $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
                $product = mysqli_fetch_assoc($res);
                if(!$product) continue;

                $total = $product['price'] * $qty;
                $grandTotal += $total;
            ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>Rs. <?= number_format($product['price'], 2) ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $id ?>">
                        <input type="number" name="quantity" value="<?= $qty ?>" min="0" class="qty-input">
                        <button type="submit" name="update_qty" class="action-btn">
                            <i class="fa fa-sync-alt"></i> Update
                        </button>
                    </form>
                </td>
                <td>Rs. <?= number_format($total, 2) ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $id ?>">
                        <button type="submit" name="delete_product" class="action-btn delete">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td colspan="2"><strong>Rs. <?= number_format($grandTotal, 2) ?></strong></td>
            </tr>
        </table>

        <div class="cart-actions">
            <a href="home.php" class="action-btn">
                <i class="fa fa-arrow-left"></i> Continue Shopping
            </a>

            <form method="post" action="payment.php">
                <button type="submit" class="action-btn">
                    <i class="fa fa-credit-card"></i> Proceed to Payment
                </button>
            </form>
        </div>

    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="home.php" class="action-btn">
            <i class="fa fa-arrow-left"></i> Go to Home
        </a>
    <?php endif; ?>
</div>

</body>
</html>

c