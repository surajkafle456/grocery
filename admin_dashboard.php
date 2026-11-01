<?php
session_start();
include "db.php";

/* =========================
   ADMIN AUTH CHECK
========================= */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

/* =========================
   FETCH PRODUCTS
========================= */
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #f2f2f2; }
        a.btn {
            padding: 7px 14px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .add { background: #28a745; }
        .edit { background: #007bff; }
        .delete { background: #dc3545; }
        .orders { background: #4CAF50; }
        .logout { background: #6c757d; }
    </style>
</head>

<body>

<h2>🛠 Admin Dashboard</h2>

<!-- TOP ACTION BUTTONS -->
<div style="margin-bottom:15px;">
    <a href="admin_add.php" class="btn add">➕ Add Product</a>
    <a href="view_reciept.php" class="btn orders">📄 View Orders</a>
    <a href="admin_logout.php" class="btn logout"
       onclick="return confirm('Logout from admin panel?')">🔒 Logout</a>
</div>

<!-- PRODUCTS TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price (Rs)</th>
    <th>Stock</th>
    <th>Status</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= number_format($row['price'], 2) ?></td>
    <td><?= $row['stock'] ?></td>

    <td>
        <?= ($row['stock'] > 0)
            ? "<span style='color:green'>In Stock</span>"
            : "<span style='color:red'>Out of Stock</span>"; ?>
    </td>

    <td>
        <a class="btn edit" href="edit_product.php?id=<?= $row['id'] ?>">✏ Edit</a>
    </td>

    <td>
        <a class="btn delete"
           href="delete_product.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Delete this product?')">❌ Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>



