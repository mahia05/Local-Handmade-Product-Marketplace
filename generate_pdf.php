<?php
require('fpdf/fpdf.php');
include 'db.php';
session_start();

if (!isset($_POST['user_id'])) {
    echo "No user ID provided.";
    exit;
}

$user_id = intval($_POST['user_id']);

// Fetch user
$userQuery = $conn->query("SELECT fullname, email FROM users WHERE id = $user_id");
$user = $userQuery->fetch_assoc();
$fullname = $user['fullname'];
$email = $user['email'];

// Fetch order data
$orderQuery = $conn->query("SELECT o.product_id, o.quantity, o.created_at, p.name, p.price
                            FROM orders o 
                            JOIN products p ON o.product_id = p.id 
                            WHERE o.user_id = $user_id 
                            ORDER BY o.created_at DESC 
                            LIMIT 10");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Receipt', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Name: $fullname", 0, 1);
$pdf->Cell(0, 10, "Email: $email", 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Product', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Cell(40, 10, 'Order Time', 1);
$pdf->Ln();

$total = 0;
$pdf->SetFont('Arial', '', 12);
while ($row = $orderQuery->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $pdf->Cell(50, 10, $row['name'], 1);
    $pdf->Cell(30, 10, "৳" . $row['price'], 1);
    $pdf->Cell(30, 10, $row['quantity'], 1);
    $pdf->Cell(40, 10, "৳" . $subtotal, 1);
    $pdf->Cell(40, 10, $row['created_at'], 1);
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 10, 'Total', 1);
$pdf->Cell(40, 10, "৳" . $total, 1);
$pdf->Cell(40, 10, '', 1);
$pdf->Ln();

$pdf->Output('D', 'Receipt.pdf');
