<?php
session_start();
require 'php/db.php'; // Database connection

// Show errors (only for development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // ✅ Save user info in session
                $_SESSION['user_id'] = $user['id'];       // Important for orders
                $_SESSION['email']   = $user['email'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['user']    = $user;             // full array (optional)

                $_SESSION['success'] = "Login successful!";
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['error'] = "Invalid password.";
            }
        } else {
            $_SESSION['error'] = "Account not found. Please register.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Zippy</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <style>
    body { background-color: #e04f56; }
    .account-container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; }
    .account-form h2 { text-align: center; margin-bottom: 20px; color: #e04f56; }
    .input-group { margin-bottom: 15px; }
    .input-group label { display: block; margin-bottom: 5px; font-weight: 600; }
    .input-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
    .btn { width: 100%; padding: 12px; background: #e04f56; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
    .btn:hover { background: #c43b46; }
    .toggle-password { cursor: pointer; font-size: 13px; margin-left: 8px; color: #555; }
    .forgot-link, .register-link { text-align: center; margin-top: 10px; }
    .forgot-link a, .register-link a { color: #e04f56; text-decoration: none; font-weight: bold; }
    p { margin: 10px 0; font-size: 14px; }
  </style>
</head>
<body>

<header>
  <a href="#" class="logo"><i class="fas fa-utensils"></i>Zippy</a>
  <nav class="navbar">
    <a href="index.php">home</a>
    <a href="speciality.php">speciality</a>
    <a href="popular.php">popular</a>
    <a href="gallery.php">gallery</a>
    <a href="about.php">About Us</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="profile.php">account</a>
    <?php else: ?>
      <a href="login.php">login</a>
    <?php endif; ?>
    <a href="cart.php" class="cart-icon"><i class="fas fa-shopping-cart"></i><span id="cart-count">0</span></a>
  </nav>
</header>

<section class="account-container">
  <form class="account-form" action="" method="POST">
    <h2>Login</h2>

    <!-- Messages -->
    <?php if (isset($_SESSION['error'])): ?>
      <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php elseif (isset($_SESSION['success'])): ?>
      <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <div class="input-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required placeholder="Enter your email" />
    </div>

    <div class="input-group">
      <label for="password">Password <span id="togglePassword" class="toggle-password">Show</span></label>
      <input type="password" id="password" name="password" required placeholder="Enter password" />
    </div>

    <div class="forgot-link">
      <a href="forgot.php">Forgot Password?</a>
    </div>

    <button type="submit" class="btn">Login</button>

    <div class="register-link">
      Don't have an account? <a href="register.php">Register here</a>
    </div>
  </form>
</section>

<script>
  const togglePassword = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("password");

  togglePassword.addEventListener("click", () => {
    const type = passwordInput.type === "password" ? "text" : "password";
    passwordInput.type = type;
    togglePassword.textContent = type === "password" ? "Show" : "Hide";
  });
</script>

</body>
</html>
