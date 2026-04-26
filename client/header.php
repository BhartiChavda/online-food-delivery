<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zippy Food - Home</title>

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css"> <!-- Update path if your CSS is in css/style.css -->

  <!-- Custom JS -->
  <script src="script.js" defer></script> <!-- Update path if your JS is in js/script.js -->
</head>
<body>

<header>
    <a href="index.php" class="logo"><i class="fas fa-utensils"></i>Zippy</a>
    <div id="menu-bar" class="fas fa-bars"></div>

    <nav class="navbar">
      <a href="index.php">home</a>
      <a href="speciality.php">speciality</a>
      <a href="popular.php">popular</a>
      <a href="gallery.php">gallery</a>
      <a href="about.php">About Us</a>

      <?php if (isset($_SESSION['user'])): ?>
        <!-- ✅ Profile Dropdown -->
        <div class="dropdown" style="display:inline-block; position:relative;">
            <a href="#" class="dropdown-toggle" style="text-decoration:none;">
              <i class="fas fa-user-circle" style="font-size: 22px; color:#e04f56;"></i>
            </a>
            <ul class="dropdown-menu" style="position:absolute; top:30px; right:0; background:white; 
                  border:1px solid #ddd; list-style:none; padding:8px; border-radius:8px; display:none; min-width:140px;">
                <li><a href="profile.php" style="display:block; padding:6px; color:#333;">Profile</a></li>
                <li><a href="my-orders.php" style="display:block; padding:6px; color:#333;">My Orders</a></li> <!-- ✅ Added -->
                <li><a href="logout.php" style="display:block; padding:6px; color:#333;">Logout</a></li>
            </ul>
        </div>

        <script>
          // Small JS for dropdown toggle
          document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".dropdown-toggle");
            const menu = document.querySelector(".dropdown-menu");

            toggle.addEventListener("click", (e) => {
              e.preventDefault();
              menu.style.display = (menu.style.display === "block") ? "none" : "block";
            });

            // Close dropdown if clicked outside
            document.addEventListener("click", (e) => {
              if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
              }
            });
          });
        </script>
      <?php else: ?>
        <a href="login.php">login</a>
      <?php endif; ?>

      <a href="cart.php" class="cart-icon">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-count">0</span>
      </a>
    </nav>
</header>
