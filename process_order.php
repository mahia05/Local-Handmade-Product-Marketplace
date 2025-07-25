<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    echo "Please login first.";
    exit();
}

$email = $_SESSION['email'];

// Get user info
$stmtUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmtUser->bind_param("s", $email);
$stmtUser->execute();
$userResult = $stmtUser->get_result();

if ($userResult->num_rows === 0) {
    echo "User not found.";
    exit();
}

$user = $userResult->fetch_assoc();
$user_id = $user['id'];

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit();
}

// Filter only valid product IDs
$cart = $_SESSION['cart'];
$validCart = [];
$productIds = array_keys($cart);

// Prepare SQL like: SELECT id FROM products WHERE id IN (1,2,5)
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$types = str_repeat('i', count($productIds));

$stmt = $conn->prepare("SELECT id FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$productIds);
$stmt->execute();
$result = $stmt->get_result();

$validIds = [];
while ($row = $result->fetch_assoc()) {
    $validIds[] = $row['id'];
}

// Keep only valid product_ids
foreach ($productIds as $pid) {
    if (in_array($pid, $validIds)) {
        $validCart[$pid] = $cart[$pid];
    }
}

if (empty($validCart)) {
    echo "No valid items to order.";
    $_SESSION['cart'] = []; // Clear cart anyway
    exit();
}

// Order placing
foreach ($validCart as $product_id => $quantity) {
    $stmtInsert = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
    $stmtInsert->bind_param("iii", $user_id, $product_id, $quantity);
    $stmtInsert->execute();
}

// Clear cart after order
$_SESSION['cart'] = [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f0fff0;
            text-align: center;
        }

        .message {
            font-size: 24px;
            color: green;
            margin-bottom: 20px;
        }

        .redirect {
            font-size: 16px;
            color: #555;
        }
    </style>
    <meta http-equiv="refresh" content="3;url=receipt.php" />
</head>

<body>
    <div class="message">✅ Order placed successfully!</div>
    <div class="redirect">You will be redirected to your receipt shortly...</div>
    <p>If not redirected, <a href="receipt.php">click here</a>.</p>
</body>

</html>