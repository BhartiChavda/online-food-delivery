<?php
session_start();
require 'php/db.php'; // Database connection

// Show errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validation
    if (empty($name) || empty($email) || empty($mobile) || empty($password) || empty($confirm)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
    } elseif (!preg_match("/^\d{10}$/", $mobile)) {
        $_SESSION['error'] = "Mobile number must be exactly 10 digits.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/", $password)) {
        $_SESSION['error'] = "Password must be 8+ chars, 1 capital, 1 number, 1 special symbol.";
    } elseif ($password !== $confirm) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Account already exists. Please login.";
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, mobile, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $mobile, $hashed);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['error'] = "Something went wrong. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | Zippy</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <style>
    body { background-color: #e04f56; }
    .error { color: red; font-size: 14px; margin-top: 5px; }
    .success { color: green; font-size: 14px; margin-top: 5px; }
  </style>
</head>
<body>

<header>
  <a href="#" class="logo"><i class="fas fa-utensils"></i>Zippy</a>
  <nav class="navbar">
    <a href="index.php">home</a>
    <a href="speciallity.php">speciality</a>
    <a href="popular.php">popular</a>
    <a href="gallery.php">gallery</a>
    <a href="about.php">About Us</a>
    <?php if (isset($_SESSION['user'])): ?>
      <a href="profile.php">account</a>
    <?php else: ?>
      <a href="login.php">login</a>
    <?php endif; ?>
    <a href="cart.php" class="cart-icon"><i class="fas fa-shopping-cart"></i><span id="cart-count">0</span></a>
  </nav>
</header>

<section class="account-container">
  <div class="register-form">
  <form class="account-form" method="POST" id="registerForm" action="">
    <h2>Create Account</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php elseif (isset($_SESSION['success'])): ?>
      <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <div class="input-group">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required placeholder="Your name" />
      <div class="error" id="nameError"></div>
    </div>

    <div class="input-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required placeholder="Your email" />
      <div class="error" id="emailError"></div>
    </div>

    <div class="input-group">
      <label for="mobile">Mobile Number</label>
      <input type="text" id="mobile" name="mobile" required placeholder="Your mobile number" maxlength="10" oninput="validateMobile(this)" />
      <div class="error" id="mobileError"></div>
    </div>

    <div class="input-group password-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required placeholder="Create a password" />
      <div class="error" id="passwordError"></div>
    </div>

    <div class="input-group password-group">
      <label for="confirm-password">Confirm Password</label>
      <input type="password" id="confirm-password" name="confirm_password" required placeholder="Confirm password" />
      <div class="error" id="confirmError"></div>
    </div>

    <button type="submit" class="btn login-btn">Register</button>

    <div class="register-link">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </form>
   </div>
</section>

<script>
function validateMobile(input) {
    // Allow only numbers
    input.value = input.value.replace(/[^0-9]/g, '');
    // Limit to 10 digits
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}

document.getElementById('registerForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Clear old errors
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (!name) {
        document.getElementById('nameError').textContent = 'Full name is required';
        isValid = false;
    }

    if (!email) {
        document.getElementById('emailError').textContent = 'Email is required';
        isValid = false;
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById('emailError').textContent = 'Invalid email format';
        isValid = false;
    }

    if (!mobile) {
        document.getElementById('mobileError').textContent = 'Mobile number is required';
        isValid = false;
    } else if (mobile.length !== 10) {
        document.getElementById('mobileError').textContent = 'Mobile number must be exactly 10 digits';
        isValid = false;
    }

    if (!password) {
        document.getElementById('passwordError').textContent = 'Password is required';
        isValid = false;
    } else if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password must be at least 8 characters';
        isValid = false;
    }

    if (confirmPassword !== password) {
        document.getElementById('confirmError').textContent = 'Passwords do not match';
        isValid = false;
    }

    if (!isValid) event.preventDefault();
});
</script>

</body>
</html>
