<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['email'])) {
    echo "Please login first.";
    exit();
}

$email = $_SESSION['email'];

// Get user info (without fullname)
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

// Get recent orders including fullname from orders table
$stmtOrders = $conn->prepare("
    SELECT o.id, o.product_id, o.quantity, o.created_at, o.fullname, o.address, o.payment_method, p.name, p.price 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC 
    LIMIT 10
");
$stmtOrders->bind_param("i", $user_id);
$stmtOrders->execute();
$orderResult = $stmtOrders->get_result();

$fullname = 'Customer'; // default fallback fullname
if ($orderResult->num_rows > 0) {
    $row = $orderResult->fetch_assoc();
    if (!empty($row['fullname'])) {
        $fullname = $row['fullname'];
    }
    // reset pointer for displaying all orders
    $orderResult->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>🧾 Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #aaa;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ddd;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .download-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>🧾 Order Receipt</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($fullname); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <hr />

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price (৳)</th>
                <th>Quantity</th>
                <th>Subtotal (৳)</th>
                <th>Order Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if ($orderResult) {
                while ($row = $orderResult->fetch_assoc()) {
                    $name = htmlspecialchars($row['name']);
                    $price = number_format($row['price'], 2);
                    $quantity = (int)$row['quantity'];
                    $subtotal = $row['price'] * $quantity;
                    $total += $subtotal;
                    $created_at = htmlspecialchars($row['created_at']);

                    echo "<tr>
                        <td>$name</td>
                        <td>৳$price</td>
                        <td>$quantity</td>
                        <td>৳" . number_format($subtotal, 2) . "</td>
                        <td>$created_at</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders found.</td></tr>";
            }
            ?>
            <tr class="total-row">
                <td colspan="3">Total:</td>
                <td colspan="2">৳<?php echo number_format($total, 2); ?></td>
            </tr>
        </tbody>
    </table>
</body>

</html>