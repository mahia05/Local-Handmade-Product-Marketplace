<?php
// products.php
session_start();
include 'db.php';

// Search filter
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Handmade Products</title>
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
            position: relative;
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            text-align: center;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 16px;
            transition: transform 0.3s ease;
        }

        .product-card img:hover {
            transform: scale(1.05);
        }

        .product-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #002f6c;
            margin-bottom: 8px;
        }

        .product-card p {
            font-size: 18px;
            font-weight: 600;
            color: #444;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
        }

        h1 {
            margin: auto;
            text-align: center;
        }

        .add-to-cart {
            background: linear-gradient(135deg, rgb(11, 70, 70), rgb(17, 85, 85));
            color: white;
            font-size: 14px;
            font-weight: 600;
            border: none;
            padding: 10px 16px;
            border-radius: 40px;
            cursor: pointer;
            width: auto;
            min-width: 130px;
            box-shadow: 0 3px 8px rgba(11, 70, 70, 0.3);
            transition: all 0.3s ease;
        }

        .add-to-cart:hover {
            background: linear-gradient(135deg, rgb(9, 58, 58), rgb(14, 70, 70));
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(11, 70, 70, 0.4);
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        .search-bar input[type="text"] {
            padding: 12px 20px;
            width: 350px;
            border: 2px solid #333;
            border-radius: 25px 0 0 25px;
            outline: none;
            font-size: 16px;
        }

        .search-bar button {
            padding: 12px 20px;
            background-color: #0b4646;
            color: white;
            border: none;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            font-size: 16px;
        }

        .search-bar button:hover {
            background-color: #093a3a;
        }

        .cart-icon {
            position: absolute;
            right: 20px;
            top: 10px;
            color: white;
            font-size: 22px;
            text-decoration: none;
        }

        .cart-icon:hover {
            text-decoration: underline;
        }

        .admin-back {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: red;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
    </style>
</head>

<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php" class="cart-icon">🛒</a>
        <a href="signup.html">Sign Up</a>
        <a href="login.html">Login</a>
    </nav>

    <?php if (isset($_SESSION['admin'])): ?>
        <a href="admin_dashboard.php" class="admin-back">⬅ Back to Dashboard</a>
    <?php endif; ?>

    <h1>Our Handmade Products</h1>

    <div class="search-bar">
        <form method="GET" action="products.php">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="products-container">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="product-card">
                <img src="images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">
                <h3>' . htmlspecialchars($row['name']) . '</h3>
                <p>' . htmlspecialchars($row['price']) . ' ৳</p>';

            // Admin cannot add to cart
            if (!isset($_SESSION['admin'])) {
                echo '
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="' . $row['id'] . '">
                    <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>';
            } else {
                echo '<p style="color:red; font-weight:bold;">(Admin cannot add to cart)</p>';
            }

            echo '</div>';
        }
        ?>
    </div>

</body>

</html>