<?php
include "db.php";

/* =========================
   1️⃣ CHECK PRODUCT ID
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

/* =========================
   2️⃣ FETCH PRODUCT
========================= */
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Product not found!";
    exit();
}

$product = mysqli_fetch_assoc($result);
$error = "";

/* =========================
   3️⃣ UPDATE PRODUCT
========================= */
if (isset($_POST['update'])) {

    $name  = trim($_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    /* ===== SERVER-SIDE VALIDATION ===== */
    if ($price <= 0) {
        $error = "Price must be a positive number!";
    } elseif ($stock <= 0) {
        $error = "Stock must be a positive number!";
    } else {

        $name  = mysqli_real_escape_string($conn, $name);
        $price = floatval($price);
        $stock = intval($stock);

        $image_name = $product['image'];

        /* ===== IMAGE UPLOAD (OPTIONAL) ===== */
        if (!empty($_FILES['image']['name'])) {

            $target_dir = "C:/xampp/htdocs/grocery/images/";
            $image_name = time() . "_" . basename($_FILES['image']['name']);
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // optional: delete old image
                if (!empty($product['image']) && file_exists($target_dir . $product['image'])) {
                    unlink($target_dir . $product['image']);
                }
            } else {
                $error = "Failed to upload image!";
            }
        }

        /* ===== UPDATE QUERY ===== */
        if (empty($error)) {
            $sql = "UPDATE products 
                    SET name='$name', price=$price, stock=$stock, image='$image_name'
                    WHERE id=$id";
            mysqli_query($conn, $sql);
            header("Location: admin_dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>

<h2>Edit Product</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><strong><?= htmlspecialchars($error) ?></strong></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <label>Product Name</label><br>
    <input type="text" name="name"
           value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

    <label>Price</label><br>
    <input type="number" name="price"
           step="0.01" min="0.01"
           value="<?= htmlspecialchars($product['price']) ?>" required><br><br>

    <label>Stock</label><br>
    <input type="number" name="stock"
           min="1"
           value="<?= htmlspecialchars($product['stock']) ?>" required><br><br>

    <label>Product Image</label><br>
    <input type="file" name="image"><br><br>

    <?php if (!empty($product['image']) && file_exists("C:/xampp/htdocs/grocery/images/" . $product['image'])): ?>
        <img src="file:///C:/xampp/htdocs/grocery/images/<?= htmlspecialchars($product['image']) ?>" width="100"><br><br>
    <?php endif; ?>

    <button type="submit" name="update">Update Product</button>

</form>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

</bod


