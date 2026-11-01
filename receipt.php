<?php
session_start();
include "db.php";

/* =========================
   COMMON VARIABLES
========================= */
$order_id   = "";
$order_date = "";
$total      = 0;

/* =========================
   USER RECEIPT LOGIC
========================= */
if (isset($_SESSION['user_id']) && isset($_SESSION['cart']) && isset($_SESSION['delivery_details'])) {

    $cart     = $_SESSION['cart'];
    $delivery = $_SESSION['delivery_details'];

    $full_name    = $delivery['full_name'] ?? '';
    $contact      = $delivery['contact'] ?? '';
    $city         = $delivery['city'] ?? '';
    $municipality = $delivery['municipality'] ?? '';
    $ward         = $delivery['ward'] ?? '';
    $address      = $delivery['address'] ?? '';

    $order_id   = "ORD-" . time();
    $order_date = date("Y-m-d H:i:s");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish_order'])) {
        $user_id = $_SESSION['user_id'];

        // Calculate total & check stock
        foreach ($cart as $pid => $qty) {
            $stmt = $conn->prepare("SELECT name, price, stock FROM products WHERE id=?");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            if ($row['stock'] < $qty) {
                die("Insufficient stock for product: " . htmlspecialchars($row['name']));
            }

            $total += $row['price'] * $qty;
        }

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("ids", $user_id, $total, $order_date);
        $stmt->execute();
        $order_db_id = $stmt->insert_id;

        // Insert order items and update stock
        foreach ($cart as $pid => $qty) {
            $stmt = $conn->prepare("SELECT price, stock FROM products WHERE id=?");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            $price     = $row['price'];
            $new_stock = $row['stock'] - $qty;

            // Update stock
            $stmt = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
            $stmt->bind_param("ii", $new_stock, $pid);
            $stmt->execute();

            // Insert order item
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_db_id, $pid, $qty, $price);
            $stmt->execute();
        }

        // Clear session
        unset($_SESSION['cart']);
        unset($_SESSION['delivery_details']);

        header("Location: home.php");
        exit;
    }
}

/* =========================
   ADMIN ORDERS LOGIC
========================= */
if (isset($_SESSION['admin'])) {

    // Delete single order item
    if (isset($_POST['delete_item'])) {
        $item_id = intval($_POST['item_id']);
        $stmt = $conn->prepare("DELETE FROM order_items WHERE id=?");
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    // Fetch all orders with latest delivery address
    $sql = "
    SELECT
        o.id AS order_id,
        o.total_amount,
        o.order_date,
        oi.id AS order_item_id,
        oi.quantity,
        oi.price,
        p.name AS product_name,
        d.full_name,
        d.contact,
        d.city,
        d.municipality,
        d.ward,
        d.address
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    LEFT JOIN delivery_addresses d 
        ON d.user_id = o.user_id
        AND d.id = (
            SELECT id FROM delivery_addresses
            WHERE user_id = o.user_id
            ORDER BY created_at DESC
            LIMIT 1
        )
    ORDER BY o.order_date DESC
    ";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?= isset($_SESSION['admin']) ? "Admin Orders" : "Receipt" ?></title>
<style>
body{font-family:Segoe UI, Arial, sans-serif; background:#f2f2f2;}
.container{width:95%; margin:30px auto; max-width:1000px;}
.card{background:#fff; padding:15px; border-radius:8px; box-shadow:0 0 8px rgba(0,0,0,.12); margin-bottom:12px;}
h3{color:#28a745; margin:0 0 8px; border-bottom:1px solid #28a745; padding-bottom:4px; font-size:17px;}
p{margin:4px 0; font-size:14px;}
table{width:100%; border-collapse:collapse; font-size:13px; background:#fff;}
th, td{border:1px solid #ccc; padding:6px; text-align:center;}
th{background:#28a745; color:#fff;}
.total{text-align:right; font-weight:bold; margin-top:8px;}
.btn{width:100%; padding:8px; margin-top:10px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer;}
.back{display:inline-block;text-decoration:none;padding:8px 12px;background:#007bff;color:#fff;border-radius:4px;margin-bottom:12px;}
.delete-btn{background:#dc3545;color:#fff;border:none;padding:6px 10px;cursor:pointer;border-radius:4px;}
.delete-btn:hover{background:#c82333;}
</style>
</head>
<body>
    <?php include "nav_bar.php"; ?>
<div class="container">

<?php if (isset($_SESSION['user_id'])): ?>
    <!-- USER RECEIPT -->
    <div class="card">
        <h3>Receipt</h3>
        <p><b>Order ID:</b> <?= $order_id ?></p>
        <p><b>Date:</b> <?= $order_date ?></p>
    </div>

    <div class="card">
        <h3>Delivery</h3>
        <p><?= htmlspecialchars($full_name) ?></p>
        <p><?= htmlspecialchars($contact) ?></p>
        <p><?= htmlspecialchars($city) ?>, <?= htmlspecialchars($municipality) ?> - <?= htmlspecialchars($ward) ?></p>
        <p><?= htmlspecialchars($address) ?></p>
    </div>

    <div class="card">
        <h3>Products</h3>
        <table>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Amount</th>
            </tr>
            <?php 
            $total = 0;
            foreach ($cart as $pid => $qty):
                $q = mysqli_query($conn,"SELECT name, price FROM products WHERE id=$pid");
                $row = mysqli_fetch_assoc($q);
                $sub = $row['price'] * $qty;
                $total += $sub;
            ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $qty ?></td>
                <td>Rs. <?= number_format($sub,2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="total">Total: Rs. <?= number_format($total,2) ?></div>
        <form method="post">
            <button class="btn" name="finish_order">Finish</button>
        </form>
    </div>

<?php elseif (isset($_SESSION['admin'])): ?>
    <!-- ADMIN ORDERS -->
    <a href="admin_dashboard.php" class="back">⬅ Back</a>
    <h2>All Orders / Receipts</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Item Total</th>
            <th>Order Total</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['order_id'] ?></td>
                <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['contact'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['city'] ?? '') ?>, <?= htmlspecialchars($row['municipality'] ?? '') ?>, Ward <?= htmlspecialchars($row['ward'] ?? '') ?><br><?= htmlspecialchars($row['address'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['product_name'] ?? 'N/A') ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($row['price'],2) ?></td>
                <td><?= number_format($row['price'] * $row['quantity'],2) ?></td>
                <td><?= number_format($row['total_amount'],2) ?></td>
                <td><?= $row['order_date'] ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Remove this item?');">
                        <input type="hidden" name="item_id" value="<?= $row['order_item_id'] ?>">
                        <button type="submit" name="delete_item" class="delete-btn">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="11">No orders found</td></tr>
        <?php endif; ?>
    </table>

<?php else: ?>
    <p>Please log in to view this page.</p>
<?php endif; ?>

</div>
</body>
</html>







