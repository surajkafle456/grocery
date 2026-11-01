<?php
include "db.php";

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $delete_query = "DELETE FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $delete_query);

    if ($result) {
        // Redirect back with a success flag
        header("Location: admin_dashboard.php?deleted=1");
        exit;
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
} else {
    echo "No product ID provided.";
}
?>

