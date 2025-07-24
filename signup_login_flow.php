<?php
session_start();
include 'db.php';

$step = isset($_GET['step']) ? $_GET['step'] : 'signup';
$error = '';
$success = '';

// Handle signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "⚠️ Email already registered. Please login.";
        $step = 'login';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        if ($stmt->execute()) {
            $success = "✅ Registration complete. Now login.";
            $step = 'login';
        } else {
            $error = "❌ Registration failed.";
        }
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);

    if ($stmt->num_rows === 1) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $success = "✅ Login successful!";

            // Redirect back to add_to_cart.php to finish cart addition
            header("Location: add_to_cart.php");
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Signup/Login</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
        }

        .btn {
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            width: 100%;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .msg {
            margin-top: 10px;
            color: green;
        }

        .error {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2><?= $step === 'signup' ? 'Sign Up' : 'Login' ?></h2>

        <?php if ($success): ?>
            <p class="msg"><?= $success ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($step === 'signup'): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required />
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required />
                </div>
                <button type="submit" name="signup" class="btn">Sign Up</button>
            </form>
            <p style="text-align:center; margin-top:10px;">Already registered? <a href="?step=login">Login here</a></p>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required />
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required />
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
            <p style="text-align:center; margin-top:10px;">New user? <a href="?step=signup">Register here</a></p>
        <?php endif; ?>
    </div>

</body>

</html>