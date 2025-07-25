<?php
include 'db.php';
session_start();
include 'header.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;

            echo "Login successful!";
            // Optionally, you can add a link to go somewhere:
            echo "<br><a href='index.html'>Go to Home</a>";
        } else {
            echo "Invalid email or password.";
            echo "<br><a href='login.html'>Try Again</a>";
        }
    } else {
        echo "Invalid email or password.";
        echo "<br><a href='login.html'>Try Again</a>";
    }
} else {
    header("Location: login.html");
    exit();
}
