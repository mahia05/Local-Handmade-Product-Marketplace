<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Handmade Products</title>
    <style>
        .product-card {
            border: 1px solid #ccc;
            padding: 15px;
            width: 200px;
            margin: 15px;
            display: inline-block;
            text-align: center;
            border-radius: 10px;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 300px;
            border-radius: 10px;
        }

        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }

        button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        input {
            width: 100%;
            padding: 6px;
        }

        a {
            color: blue;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h2>🛍 Handmade Products</h2>

    <?php
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $name = htmlspecialchars($row['name']);
            $price = htmlspecialchars($row['price']);
            $image = htmlspecialchars($row['image']);

            echo "
        <div class='product-card'>
            <img src='images/$image' alt='$name' width='150' height='150' />
            <h3>$name</h3>
            <p>$price ৳</p>
            <button onclick='handleAddToCart($id)'>Add to Cart</button>
        </div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>

    <!-- 🔐 Signup Modal -->
    <div id="signupModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('signupModal')">&times;</span>
            <h3>Sign Up</h3>
            <form method="POST" action="signup.php" onsubmit="signupSubmitted()">
                <input type="text" name="fullname" placeholder="Full Name" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a onclick="showLogin()">Login here</a></p>
        </div>
    </div>

    <!-- 🔐 Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginModal')">&times;</span>
            <h3>Login</h3>
            <form method="POST" action="login.php" onsubmit="loginSubmitted()">
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <script>
        function handleAddToCart(productId) {
            fetch('check_login_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        window.location.href = 'add_to_cart.php?product_id=' + productId;
                    } else {
                        localStorage.setItem("pendingProduct", productId);
                        document.getElementById("signupModal").style.display = "block";
                    }
                });
        }

        function signupSubmitted() {
            localStorage.setItem("signupSuccess", "true");
        }

        function loginSubmitted() {
            localStorage.setItem("loginSuccess", "true");
        }

        window.onload = function() {
            if (localStorage.getItem("signupSuccess") === "true") {
                localStorage.removeItem("signupSuccess");
                closeModal("signupModal");
                document.getElementById("loginModal").style.display = "block";
            }

            if (localStorage.getItem("loginSuccess") === "true") {
                localStorage.removeItem("loginSuccess");
                let productId = localStorage.getItem("pendingProduct");
                if (productId) {
                    localStorage.removeItem("pendingProduct");
                    window.location.href = 'add_to_cart.php?product_id=' + productId;
                }
            }
        }

        function showLogin() {
            closeModal("signupModal");
            document.getElementById("loginModal").style.display = "block";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>

</body>

</html>