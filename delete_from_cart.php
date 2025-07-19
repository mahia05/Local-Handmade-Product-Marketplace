<?php
session_start();

if (isset($_POST['product_id']) && isset($_SESSION['cart'])) {
    $id = $_POST['product_id'];
    $index = array_search($id, $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reset keys
    }
}

header("Location: cart.php");
exit();
