<?php
require 'con.php';

$username = "admin";
$email = "zippy@gmail.com";
$password = password_hash("zippy@123", PASSWORD_DEFAULT);

// Check if username already exists
$stmt = $con->prepare("SELECT id FROM admin_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin user already exists.";
} else {
    $stmt = $con->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        echo "Admin user created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$con->close();
?>
