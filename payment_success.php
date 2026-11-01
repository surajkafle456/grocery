<?php
session_start();

/* Block direct access */
if (!isset($_SESSION['payment_done'])) {
    header("Location: home.php");
    exit;
}

// Remove flag so refresh won't repeat
unset($_SESSION['payment_done']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
</head>
<body>

<h2>✅ Payment Successful!</h2>

<p>Your order has been placed successfully.</p>

<a href="home.php">
    <button>Back to Home</button>
</a>

<a href="logout.php">
    <button>Logout</button>
</a>

</body>
</html>
