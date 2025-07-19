<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "local_handmade_product_marketplace";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
