<?php
// products.php
session_start();
include 'db.php'; // Make sure db.php connects to your database properly
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
    </style>
</head>

<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.html">Cart</a>
        <a href="signup.html">Sign Up</a>
        <a href="login.html">Login</a>
    </nav>

    <h1>Our Handmade Products</h1>

    <div class="products-container">
        <?php
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="product-card">
                <img src="images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">
                <h3>' . htmlspecialchars($row['name']) . '</h3>
                <p>' . htmlspecialchars($row['price']) . ' ৳</p>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="' . $row['id'] . '">
                    <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>
            </div>
            ';
        }
        ?>
    </div>

</body>

</html>