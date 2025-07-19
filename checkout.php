<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    echo "Please log in first.";
    exit();
}

$email = $_SESSION['email'];
$userResult = $conn->query("SELECT id FROM users WHERE email='$email'");
$user = $userResult->fetch_assoc();
$user_id = $user['id'];

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
} else {
    foreach ($_SESSION['cart'] as $product_id) {
        $conn->query("INSERT INTO orders (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
    $_SESSION['cart'] = []; // clear cart after order
    echo "Order placed successfully!";
}
