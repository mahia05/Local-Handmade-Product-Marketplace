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

// Insert orders
$allOrdersSuccess = true;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Check product exists
    $stmtCheck = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmtCheck->bind_param("i", $product_id);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();

    if ($resCheck->num_rows === 0) {
        $allOrdersSuccess = false;
        continue; // skip invalid product
    }

    // Insert order
    $stmtInsert = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
    $stmtInsert->bind_param("iii", $user_id, $product_id, $quantity);
    if (!$stmtInsert->execute()) {
        $allOrdersSuccess = false;
    }
}

if ($allOrdersSuccess) {
    // Clear cart
    $_SESSION['cart'] = [];
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
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
        <p>If you are not redirected automatically, <a href="receipt.php">click here</a>.</p>
    </body>

    </html>

<?php
} else {
    echo "⚠️ Some items could not be ordered. Please check your cart.";
}
?>