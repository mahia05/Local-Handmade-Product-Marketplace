<?php
session_start();
include 'db.php';
include 'header.php';


// Handle remove from cart
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
}

// Handle quantity update
if (isset($_POST['update_qty'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .cart-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }

        .cart-item {
            width: 280px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
        }

        .cart-item img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        .cart-item h4 {
            margin: 10px 0 5px;
        }

        .cart-item form {
            margin: 10px 0;
        }

        .cart-item input[type="number"] {
            width: 50px;
            padding: 4px;
        }

        .cart-item button {
            padding: 5px 10px;
            margin-left: 5px;
        }

        .remove-link {
            display: block;
            color: red;
            margin-top: 10px;
            text-decoration: none;
        }

        h3.total {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">🛒 Your Cart</h2>

    <div class="cart-container">
        <?php
        $total = 0;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                // Protect against SQL Injection by casting $product_id to int
                $product_id = intval($product_id);
                $sql = "SELECT * FROM products WHERE id = $product_id";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $name = htmlspecialchars($row['name']);
                    $price = floatval($row['price']);
                    $image = htmlspecialchars($row['image']);
                    $subtotal = $price * $quantity;
                    $total += $subtotal;

                    echo "
                    <div class='cart-item'>
                        <img src='images/$image' alt='$name'>
                        <h4>$name</h4>
                        <p>Price: $price tk</p>
                        <form method='POST'>
                            <input type='hidden' name='product_id' value='$product_id'>
                            <input type='number' name='quantity' value='$quantity' min='1'>
                            <button type='submit' name='update_qty'>Update</button>
                        </form>
                        <p>Subtotal: $subtotal tk</p>
                        <a href='cart.php?remove=$product_id' class='remove-link'>Remove</a>
                    </div>";
                }
            }
            echo "<h3 class='total'>Total: $total tk</h3>";
        } else {
            echo "<p style='text-align:center;'>Your cart is empty.</p>";
        }
        ?>
    </div>

    <div style="text-align:center; margin-top: 20px;">
        <a href="products.php">Continue Shopping</a> |
        <a href="checkout.php">Proceed to Checkout</a>
    </div>

</body>

</html>