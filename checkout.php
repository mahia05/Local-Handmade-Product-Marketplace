<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav>
        <a href="index.html">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="signup.html">Sign Up</a>
        <a href="login.html">Login</a>
    </nav>

    <main>
        <h2>Checkout</h2>

        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<p style='color:green;'>✅ Order placed successfully!</p>";
        }
        ?>

        <form action="process_order.php" method="POST">
            <label>Full Name:</label><br>
            <input type="text" name="fullname" required><br>

            <label>Address:</label><br>
            <textarea name="address" required></textarea><br>

            <label>Payment Method:</label><br>
            <select name="payment">
                <option value="cash">Cash on Delivery</option>
                <option value="bkash">bKash</option>
            </select><br><br>

            <input type="submit" value="Place Order">
        </form>
    </main>

</body>

</html>