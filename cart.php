<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $product_id) {
        $sql = "SELECT * FROM products WHERE id=$product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();

        echo "<div>
            <h4>{$product['name']}</h4>
            <p>{$product['price']} tk</p>
        </div>";
    }
}
