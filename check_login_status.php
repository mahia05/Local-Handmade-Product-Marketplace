<?php
session_start();
include 'db.php'; // database connection

$loggedIn = false;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Check if user still exists in DB
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $loggedIn = true;
    } else {
        // User deleted from DB — destroy session
        session_destroy();
    }
}

header('Content-Type: application/json');
echo json_encode(["loggedIn" => $loggedIn]);
