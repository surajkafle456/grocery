<?php
include "db.php";
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
<a href="view_reciept.php" 
   style="padding:8px 15px;background:#4CAF50;color:white;
   text-decoration:none;border-radius:5px;">
   📄 View Orders / Receipts
</a>
<h2>Admin Dashboard</h2>

<div style="text-align:right; margin-bottom:15px;">
    <a href="admin_logout.php"
       style="padding:8px 15px;
              background:#dc3545;
              color:white;
              text-decoration:none;
              border-radius:5px;"
       onclick="return confirm('Logout from admin panel?')">
       🔒 Logout
    </a>
</div>


<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td>Rs. <?php echo $row['price']; ?></td>
    <td><?php echo $row['stock']; ?></td>

    <td>
        <?php
        if ($row['stock'] > 0) {
            echo "<span style='color:green'>In Stock</span>";
        } else {
            echo "<span style='color:red'>Out of Stock</span>";
        }
        ?>
    </td>

    <td>
    <a href="edit_product.php?id=<?php echo $row['id']; ?>">✏ Edit</a>
        <a href="delete_product.php?id=<?= $row['id'] ?>" 
           onclick="return confirm('Are you sure?')">❌ Delete</a>
        </a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>

