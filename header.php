<?php session_start(); ?>
<nav>
    <a href="index.html">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>

    <div style="float:right;">
        <?php if (isset($_SESSION['email'])): ?>
            <div class="user-menu" style="position:relative; display:inline-block;">
                <span style="cursor:pointer;">👤 <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <div class="dropdown" style="display:none; position:absolute; right:0; background:#fff; border:1px solid #ccc;">
                    <a href="profile.php">Profile Info</a><br>
                    <a href="cart.php">Cart</a><br>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <script>
                const userMenu = document.querySelector('.user-menu');
                const dropdown = userMenu.querySelector('.dropdown');
                userMenu.addEventListener('mouseenter', () => dropdown.style.display = 'block');
                userMenu.addEventListener('mouseleave', () => dropdown.style.display = 'none');
            </script>
        <?php else: ?>
            <a href="login.html">Login</a> | <a href="signup.html">Register</a>
        <?php endif; ?>
    </div>
</nav>