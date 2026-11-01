<?php
session_start();
include "db.php";

/* =========================
   LOGIN CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'payment.php';
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =========================
   CART CHECK
========================= */
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

/* =========================
   LOAD DELIVERY DETAILS
========================= */
if (!isset($_SESSION['delivery_details'])) {

    $stmt = $conn->prepare(
        "SELECT full_name, contact, city, municipality, ward, address
         FROM delivery_addresses
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['delivery_details'] = $result->fetch_assoc();
    }
}

/* =========================
   VIEW ADDRESS TOGGLE
========================= */
$showAddress = isset($_GET['view']) && $_GET['view'] === 'address';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>

    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 { color: #28a745; }
        p { font-size: 18px; margin: 15px 0; }

        .pay-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin: 6px;
        }

        .pay-btn:hover { background-color: #218838; }

        .edit-btn {
            background: #ffc107;
            color: black;
        }

        .box {
            margin-top: 25px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f8f9fa;
            display: inline-block;
            text-align: left;
            width: 400px;
        }
    </style>
</head>

<body>
<?php include "nav_bar.php"; ?>
<h1>💳 Payment</h1>

<?php if (isset($_SESSION['delivery_details'])): ?>

    <p><strong>Delivery location ready ✔</strong></p>

    <!-- PAYMENT BUTTON -->
    <form action="receipt.php" method="post">
        <button type="submit" class="pay-btn">
            <i class="fa fa-credit-card"></i> Proceed to Payment
        </button>
    </form>

    <!-- VIEW / CLOSE ADDRESS BUTTON -->
    <?php if (!$showAddress): ?>
        <a href="payment.php?view=address" class="pay-btn edit-btn">
            <i class="fa fa-eye"></i> View Delivery Address
        </a>
    <?php else: ?>
        <a href="payment.php" class="pay-btn edit-btn">
            <i class="fa fa-times-circle"></i> Close Address
        </a>
    <?php endif; ?>

    <!-- CHANGE ADDRESS -->
    <a href="delivery_details.php" class="pay-btn edit-btn">
        <i class="fa fa-edit"></i> Change Delivery Address
    </a>

    <!-- ADDRESS DETAILS -->
    <?php if ($showAddress): ?>
        <div class="box">
            <h3>📦 Saved Delivery Address</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['delivery_details']['full_name']) ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($_SESSION['delivery_details']['contact']) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($_SESSION['delivery_details']['city']) ?></p>
            <p><strong>Municipality:</strong> <?= htmlspecialchars($_SESSION['delivery_details']['municipality']) ?></p>
            <p><strong>Ward:</strong> <?= htmlspecialchars($_SESSION['delivery_details']['ward']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($_SESSION['delivery_details']['address']) ?></p>
        </div>
    <?php endif; ?>

<?php else: ?>

    <p>No delivery address found ❌</p>

    <a href="delivery_details.php" class="pay-btn">
        <i class="fa fa-truck"></i> Add Delivery Address
    </a>

<?php endif; ?>

</body>
</html>
