<?php
session_start();
include 'db.php'; // Ensure this includes your DB connection

// Step 1: Not logged in
if (!isset($_SESSION['email'])) {
    $product_id = 0;

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
    } elseif (isset($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
    }

    if ($product_id > 0) {
        $_SESSION['pending_product_id'] = $product_id;
    }

    header('Location: signup_login_flow.php');
    exit();
}

// Step 2: Logged in – Add to cart
$product_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
} elseif (isset($_SESSION['pending_product_id'])) {
    $product_id = intval($_SESSION['pending_product_id']);
    unset($_SESSION['pending_product_id']);
}

if ($product_id > 0) {
    // ✅ Optional: Validate product ID exists in DB
    $check = mysqli_query($conn, "SELECT id FROM products WHERE id = $product_id");
    if (mysqli_num_rows($check) == 0) {
        echo "⚠ Product not found.";
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header('Location: cart.php');
    exit();
} else {
    echo "⚠ Invalid product or missing ID.";
}
