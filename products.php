<?php
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "
    <div class='product-card'>
        <img src='uploads/{$row['image']}' alt='{$row['name']}' />
        <h3>{$row['name']}</h3>
        <p>{$row['price']} tk</p>
        <form method='POST' action='add_to_cart.php'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <button type='submit'>Add to Cart</button>
        </form>
    </div>";
}
