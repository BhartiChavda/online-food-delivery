<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

// Fallback name if session variable not set
$adminName = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : "Admin";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome Admin</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($adminName); ?>!</h2>
    <p>You are now logged in to the admin dashboard.</p>
    
    <!-- Add any dashboard links or features here -->
    
    <a href="logout.php">Logout</a>
</body>
</html>
