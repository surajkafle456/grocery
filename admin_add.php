<?php
session_start();
include "db.php";

/* =========================
   ADMIN CHECK
========================= */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$error = "";

/* =========================
   ADD PRODUCT
========================= */
if (isset($_POST['add_product'])) {

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $price   = floatval($_POST['price']);
    $stock   = intval($_POST['stock']);
    $unit    = mysqli_real_escape_string($conn, $_POST['unit']);
    $visible = isset($_POST['visible']) ? 1 : 0;

    // IMAGE SELECTED FROM DROPDOWN
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    $sql = "INSERT INTO products (name, price, image, stock, visible, unit)
            VALUES ('$name', '$price', '$image', '$stock', '$visible', '$unit')";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Failed to add product!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial;
            background:#f5f5f5;
        }
        form {
            width:350px;
            margin:80px auto;
            padding:20px;
            background:white;
            border-radius:5px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }
        input, select, button {
            width:100%;
            padding:8px;
            margin:8px 0;
        }
        button {
            background:#28a745;
            color:white;
            border:none;
            cursor:pointer;
        }
    </style>
</head>
<body>

<form method="POST">
    <h3>Add Product</h3>

    <input type="text" name="name" placeholder="Product Name" required>

    <input type="number" step="0.01" name="price" placeholder="Price" required>

    <input type="number" name="stock" placeholder="Stock Quantity" required>

    <select name="unit" required>
        <option value="">-- Select Unit --</option>
        <option value="piece">Piece</option>
        <option value="kg">Kg</option>
        <option value="liter">Liter</option>
    </select>

    <!-- IMAGE SELECTION -->
    <select name="image" required>
        <option value="">-- Select Image --</option>
        <?php
        $images = scandir("images");
        foreach ($images as $img) {
            if ($img !== "." && $img !== "..") {
                echo "<option value='$img'>$img</option>";
            }
        }
        ?>
    </select>

    <label>
        <input type="checkbox" name="visible" checked> Visible
    </label>

    <button type="submit" name="add_product">Save Product</button>

    <br><br>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a>

    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
</form>

</body>
</html>






