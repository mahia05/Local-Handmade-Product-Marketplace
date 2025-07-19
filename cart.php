<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $product_id) {
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();

            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>
                    <h4>{$product['name']}</h4>
                    <p>{$product['price']} tk</p>
                    
                    <form method='POST' action='delete_from_cart.php' style='display:inline;'>
                        <input type='hidden' name='product_id' value='{$product_id}'>
                        <button type='submit'>Remove</button>
                    </form>
                  </div>";
        }
    }
}
