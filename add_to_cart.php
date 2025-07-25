<?php
session_start();
include 'header.php';

if (!isset($_SESSION['email'])) {
    // User not logged in, store pending product ID (from POST or GET)
    $product_id = 0;

    // Prefer POST product_id, fallback to GET product_id if any
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
    } elseif (isset($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
    }

    if ($product_id > 0) {
        $_SESSION['pending_product_id'] = $product_id; // store pending product to add after login/signup
    }

    // Redirect to a combined signup/login page
    header('Location: signup_login_flow.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get product_id from POST or from pending session (fallback)
    $product_id = 0;
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
    } elseif (isset($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
    } elseif (isset($_SESSION['pending_product_id'])) {
        $product_id = intval($_SESSION['pending_product_id']);
        unset($_SESSION['pending_product_id']);
    }

    if ($product_id > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Increase quantity if already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }

        // Redirect to cart page after adding
        header('Location: cart.php');
        exit();
    } else {
        echo "Invalid product.";
    }
} else {
    // If method is not POST or GET, do nothing or show error
    echo "Invalid request method.";
}
