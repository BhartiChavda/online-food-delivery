<?php
session_start();
include '../php/config.php';
$pageTitle = "ADD Food - Admin";
include 'admin-header.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $image = $_FILES['image'] ?? null;

    $allowed_categories = ['gallery', 'popular', 'speciality'];

    if (!empty($name) && !empty($price) && !empty($description) && !empty($image['name']) && in_array($category, $allowed_categories)) {
        
        // 1. Temporary Upload Folder
        $temp_upload_dir = dirname(__DIR__) . "/uploads/temp/";
        if (!is_dir($temp_upload_dir)) {
            mkdir($temp_upload_dir, 0777, true);
        }

        // Move uploaded file to temporary folder
        $temp_file = $temp_upload_dir . basename($image["name"]);
        if (!move_uploaded_file($image["tmp_name"], $temp_file)) {
            $error = "❌ Failed to move file to temporary folder!";
        } else {
            // 2. Final Upload Folder (based on category)
            $final_upload_dir = dirname(__DIR__) . "/images/" . $category . "/";
            if (!is_dir($final_upload_dir)) {
                mkdir($final_upload_dir, 0777, true);
            }

            // 3. Create unique image name
            $image_name = time() . "_" . basename($image["name"]);
            $target_file = $final_upload_dir . $image_name;

            // 4. Validate file type
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowed_extensions)) {
                if (filesize($temp_file) <= 2 * 1024 * 1024) { // Max 2MB
                    if (rename($temp_file, $target_file)) {
                        // Save relative path for DB
                        $image_path = "images/" . $category . "/" . $image_name;

                        // Insert into database
                        $stmt = $conn->prepare("INSERT INTO food_items (name, description, price, image_path, category) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssdss", $name, $description, $price, $image_path, $category);

                        if ($stmt->execute()) {
                            $success = "✅ $category food item added successfully!";
                        } else {
                            unlink($target_file);
                            $error = "❌ Database error: " . $stmt->error;
                        }
                    } else {
                        $error = "❌ Failed to move image to final folder!";
                    }
                } else {
                    unlink($temp_file);
                    $error = "❌ Image size must be under 2MB!";
                }
            } else {
                unlink($temp_file);
                $error = "❌ Invalid image format (only jpg, jpeg, png, gif)!";
            }
        }
    } else {
        $error = "❌ Please fill all fields correctly!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Food Item</title>
  <style>
      body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
      .form-container { max-width: 600px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
      h2 { text-align: center; color: #ff3838; }
      input, select, textarea, button { width: 100%; margin: 10px 0; padding: 10px; font-size: 16px; }
      button { background: #ff3838; color: #fff; border: none; cursor: pointer; }
      button:hover { background: #e33434; }
      .success { color: green; text-align: center; }
      .error { color: red; text-align: center; }
  </style>
</head>
<body>
<div class="form-container">
    <h2>Add New Food Item</h2>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <label>Food Name:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" rows="3" required></textarea>

        <label>Price (₹):</label>
        <input type="number" name="price" step="0.01" required>

        <label>Select Category:</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="popular">Popular</option>
            <option value="gallery">Gallery</option>
            <option value="speciality">Speciality</option>
        </select>

        <label>Upload Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Food</button>
    </form>
</div>
<!-- In admin-footer.php (before </body>) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

