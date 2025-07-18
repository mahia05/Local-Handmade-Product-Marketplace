<?php
session_start();
$product_id = $_POST['product_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = $product_id;
header('Location: cart.php');
