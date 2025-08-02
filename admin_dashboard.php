<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e5f7f7;
            padding: 30px;
        }

        h2 {
            color: rgb(11, 70, 70);
        }

        .nav {
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .nav a {
            background-color: rgb(11, 70, 70);
            color: white;
            padding: 10px 15px;
            margin-right: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .nav a:hover {
            background-color: #094545;
        }
    </style>
</head>

<body>

    <h2>Welcome, Admin 👩‍💼</h2>

    <div class="nav">
        <a href="admin_add_product.php">➕ Add Product</a>
        <a href="manage_product.php">🛠️ Manage Products</a>
        <a href="products.php" target="_blank">🔍 View All Products</a>
        <a href="admin_logout.php" style="float: right; background-color: #cc0000;">🚪 Logout</a>
    </div>

    <p>Select an option above to manage the handmade product marketplace.</p>

</body>

</html>