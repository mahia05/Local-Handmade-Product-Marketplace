<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0b4646;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #0b4646;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #093a3a;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: red;
        }
    </style>
</head>

<body>

    <?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['admin'])) {
        header("Location: admin_login.php");
        exit;
    }

    $message = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = floatval($_POST['price']);

        // Image Handling
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowed_types)) {
            $message = "❌ Invalid image format. Only jpg, jpeg, png, gif, webp allowed.";
        } elseif (move_uploaded_file($tmp_name, $target_file)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $name, $price, $image);
            $stmt->execute();

            header("Location: admin_dashboard.php?added=success");
            exit;
        } else {
            $message = "❌ Failed to upload image.";
        }
    }
    ?>

    <div class="form-container">
        <h2>➕ Add New Product</h2>
        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="file" name="image" required>
            <button type="submit">Add Product</button>
        </form>
    </div>

</body>

</html>