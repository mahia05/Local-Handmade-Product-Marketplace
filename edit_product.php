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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);

    // Optional: Update image if provided
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowed_types)) {
            echo "<p style='color:red;'>❌ Invalid image format.</p>";
            exit;
        }

        if (move_uploaded_file($tmp_name, $target_file)) {
            // Update with image
            $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("sdsi", $name, $price, $image, $id);
        } else {
            echo "<p style='color:red;'>❌ Failed to upload image.</p>";
            exit;
        }
    } else {
        // Update without image
        $stmt = $conn->prepare("UPDATE products SET name=?, price=? WHERE id=?");
        $stmt->bind_param("sdi", $name, $price, $id);
    }

    $stmt->execute();
    // ✅ Redirect after update
    echo "<script>
    alert('✅ Product updated successfully!');
    window.location.href = 'admin_dashboard.php';
</script>";
    exit;
}

// Get current product data
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "❌ Product not found.";
    exit;
}
?>

<h2>Edit Product</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>
    <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required><br><br>
    <img src="images/<?= $product['image'] ?>" width="150"><br><br>
    <input type="file" name="image"> (Leave blank to keep existing)<br><br>
    <button type="submit">Update Product</button>
</form>