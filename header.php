<style>
    .profile-container {
        position: absolute;
        top: 15px;
        right: 20px;
    }

    .profile-icon {
        font-size: 22px;
        cursor: pointer;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 30px;
        background-color: white;
        border: 1px solid #ddd;
        min-width: 150px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .dropdown-content a {
        padding: 10px;
        display: block;
        text-decoration: none;
        color: black;
    }

    .dropdown-content a:hover {
        background-color: #f0f0f0;
    }
</style>

<div class="profile-container">
    <div class="profile-icon" onclick="toggleDropdown()">👤</div>
    <div id="dropdown" class="dropdown-content">
        <a href="profile.php">My Profile</a>
        <a href="order_history.php">History</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("dropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Optional: hide dropdown when clicked outside
    document.addEventListener('click', function(e) {
        var dropdown = document.getElementById("dropdown");
        var icon = document.querySelector('.profile-icon');
        if (!icon.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>