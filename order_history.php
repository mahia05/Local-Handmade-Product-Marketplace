<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['email'])) {
    echo "Please log in to view your order history.";
    exit();
}

$email = $_SESSION['email'];
$userResult = $conn->query("SELECT id FROM users WHERE email='$email'");
$user = $userResult->fetch_assoc();
$user_id = $user['id'];

$sql = "SELECT products.name, products.price, orders.created_at
        FROM orders
        JOIN products ON orders.product_id = products.id
        WHERE orders.user_id = $user_id
        ORDER BY orders.created_at DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>
            <h4>{$row['name']}</h4>
            <p>Price: {$row['price']} tk</p>
            <p>Ordered On: {$row['created_at']}</p>
        </div>";
    }
} else {
    echo "No past orders found.";
}
