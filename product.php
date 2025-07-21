<?php
session_start();
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = htmlspecialchars($row['name']);
        $price = htmlspecialchars($row['price']);
        $image = htmlspecialchars($row['image']);

        echo "
        <div class='product-card'>
            <img src='images/$image' alt='$name' width='150' height='150' />
            <h3>$name</h3>
            <p>$price tk</p>
            <form method='POST' action='add_to_cart.php'>
                <input type='hidden' name='product_id' value='$id'>
                <button type='submit'>Add to Cart</button>
            </form>
        </div><br>";
    }
} else {
    echo "<p>No products found.</p>";
}
