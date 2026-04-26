<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Please login to view your profile.";
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $target_dir = "uploads/profile_images/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES['profile_image']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        // Delete old image if exists and not default
        if (!empty($user['profile_image']) && $user['profile_image'] != 'default-user.png') {
            $old_image_path = $target_dir . $user['profile_image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }

        // Update DB
        $update = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $update->bind_param("si", $file_name, $user_id);
        $update->execute();

        // Update session
        $_SESSION['user']['profile_image'] = $file_name;

        header("Location: profile.php?success=1");
        exit;
    }
}

// Handle remove image
if (isset($_GET['remove_image']) && $_GET['remove_image'] == 1) {
    if (!empty($user['profile_image']) && $user['profile_image'] != 'default-user.png') {
        $old_image_path = "uploads/profile_images/" . $user['profile_image'];
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    }

    $default_image = 'default-user.png';
    $update = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    $update->bind_param("si", $default_image, $user_id);
    $update->execute();

    $_SESSION['user']['profile_image'] = $default_image;

    header("Location: profile.php?removed=1");
    exit;
}

// Determine image path
$profile_image = (!empty($user['profile_image']) && $user['profile_image'] != 'default-user.png')
    ? "uploads/profile_images/" . $user['profile_image']
    : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile | Zippy</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
body {
    background: #f8f9fa;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}
header {
    background: #fff;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.logo {
    font-size: 24px;
    color: #e04f56;
    text-decoration: none;
    font-weight: bold;
}
.navbar a {
    margin-left: 18px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    font-size: 16px;
    transition: color 0.3s;
}
.navbar a:hover {
    color: #e04f56;
}
.profile-container {
    display: flex;
    justify-content: center;
    margin-top: 50px;
}
.profile-box {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    width: 450px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.profile-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border: 3px solid #e04f56;
    overflow: hidden;
}
.profile-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.profile-icon i {
    font-size: 50px;
    color: #bbb;
}
h2 {
    margin-bottom: 15px;
    font-size: 22px;
}
.profile-box p {
    font-size: 16px;
    margin: 8px 0;
}
.btn-remove {
    display: inline-block;
    margin: 10px 5px;
    background: #6c757d;
    color: #fff;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
    border: none;
}
.btn-remove:hover {
    background: #5a6268;
}
input[type="file"] {
    display: none;
}
.custom-file-label {
    display: inline-block;
    padding: 10px 20px;
    background: #e04f56;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    font-weight: bold;
}
.custom-file-label:hover {
    background: #c93d46;
}
footer {
    text-align: center;
    padding: 15px;
    background: #fff;
    margin-top: 50px;
    box-shadow: 0 -2px 6px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<header>
    <a href="index.php" class="logo"><i class="fas fa-utensils"></i> Zippy</a>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="my-orders.php">My Orders</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="profile-container">
    <div class="profile-box">
        <div class="profile-icon">
            <?php if ($profile_image): ?>
                <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Image">
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
        </div>
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Mobile:</strong> <?= htmlspecialchars($user['mobile']) ?></p>

        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <label class="custom-file-label">
                <input type="file" name="profile_image" onchange="this.form.submit()">
                Change Profile Picture
            </label>
        </form>

        <?php if ($profile_image): ?>
            <a href="profile.php?remove_image=1" class="btn-remove">Remove Image</a>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> Zippy. All Rights Reserved.</p>
</footer>

</body>
</html>
