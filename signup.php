<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  // Check if email already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->close();
    echo "Email already registered.";
    echo "<br><a href='signup.html'>Try Again</a>";
    exit();
  }
  $stmt->close();

  // Hash the password securely
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $password_hash);

  if ($stmt->execute()) {
    // Correct way to get last inserted id
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['email'] = $email;

    echo "Registration successful!";
    echo "<br><a href='login.html'>Click here to Login</a>";
  } else {
    echo "Database error: " . $conn->error;
  }

  $stmt->close();
} else {
  header("Location: signup.html");
  exit();
}
