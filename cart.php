<?php
session_start();
include 'db.php'; // Make sure this connects to your DB

echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    $ids = array_keys($_SESSION['cart']); // get all product_ids from session

    // Create an SQL-friendly string like (1, 2, 5)
    $id_string = implode(',', array_map('intval', $ids));

    // Fetch product details
    $sql = "SELECT * FROM products WHERE id IN ($id_string)";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $name = $row['name'];
            $price = $row['price'];
            $img = $row['image']; // assuming this is image path
            $quantity = $_SESSION['cart'][$id];
            $total = $price * $quantity;

            echo "
            <div class='product-card'>
                <img src='images/$img' width='100'>
                <h3>$name</h3>
                <p>Price: $price tk</p>
                <p>Quantity: $quantity</p>
                <p>Total: $total tk</p>
                <form method='POST' action='delete_from_cart.php'>
                    <input type='hidden' name='product_id' value='$id'>
                    <button type='submit'>Remove</button>
                </form>
            </div><hr>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
}
