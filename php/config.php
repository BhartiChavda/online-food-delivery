<?php
// Database configuration

$host = "localhost";        // Your MySQL host (usually localhost)
$user = "root";             // Your MySQL username (default is root in XAMPP)
$password = "";             // Your MySQL password (leave blank in XAMPP)
$database = "online_food_delivery"; // Your database name

// Create database connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// ✅ Connection successful
?>
