<?php
session_start(); // Needed if you use sessions in add_to_cart.php
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='product-card'>
            <img src='uploads/{$row['image']}' alt='" . htmlspecialchars($row['name']) . "' />
            <h3>" . htmlspecialchars($row['name']) . "</h3>
            <p>" . htmlspecialchars($row['price']) . " tk</p>

            <form method='POST' action='add_to_cart.php'>
                <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                <button type='submit'>Add to Cart</button>
            </form>
        </div>";
    }
} else {
    echo "<p>No products found.</p>";
}
