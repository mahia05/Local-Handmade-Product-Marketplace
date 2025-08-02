<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "❌ Invalid request.";
    exit;
}

$id = intval($_GET['id']);

// Optional: delete image from folder
$result = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);
if ($product && file_exists('images/' . $product['image'])) {
    unlink('images/' . $product['image']); // delete image file
}

// Delete product from database
mysqli_query($conn, "DELETE FROM products WHERE id=$id");

// ✅ Redirect after update
echo "<script>
    alert('✅ Product updated successfully!');
    window.location.href = 'admin_dashboard.php';
</script>";
exit;
