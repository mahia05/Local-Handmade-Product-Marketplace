<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User login nai, tai signup/login page e redirect kore dibo
    // Je page theke asche seta redirect er jonno store kore rakhi
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: signup.html');  // ba login.html o dite paren
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Jodi product already cart e thake, quantity barbe
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1; // natun product add kora hocche
    }

    header('Location: cart.php');
    exit();
} else {
    echo "Invalid request";
}
