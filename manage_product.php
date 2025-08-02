<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f7fb;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            padding: 10px 20px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 60px 20px;
        }

        .product-card {
            width: 300px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .product-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #002f6c;
        }

        .product-card p {
            font-size: 18px;
            font-weight: 600;
            color: #444;
            margin: 10px 0;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            padding: 8px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            color: white;
            transition: 0.3s;
        }

        .edit-btn {
            background-color: #007bff;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #b02a37;
        }
    </style>
</head>

<body>

    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_add_product.php">Add Product</a>
        <a href="products.php" target="_blank">View Site</a>
        <a href="admin_logout.php">Logout</a>
    </nav>

    <h1 style="text-align:center; padding: 20px;">Manage Products</h1>

    <div class="products-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product-card">
                <img src="images/<?php echo $row['image']; ?>" alt="Product Image">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><?php echo $row['price']; ?> Tk</p>
                <div class="actions">
                    <a class="edit-btn" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="delete-btn" href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </div>
            </div>
        <?php } ?>
    </div>

</body>

</html>