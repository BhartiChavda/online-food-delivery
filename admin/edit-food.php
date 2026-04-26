<?php
session_start();
include 'php/config.php';

// Get food ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Invalid Food ID");
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM food_items WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$food = $result->fetch_assoc();

if (!$food) {
    die("Food item not found.");
}

$success = $error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = $_POST['category'] ?? $food['category'];

    if (!empty($name) && is_numeric($price) && !empty($category)) {
        $image_path = $food['image_path']; // default old image

        // Check if new image uploaded
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../images/" . strtolower($category) . "/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image_name = time() . '_' . basename($_FILES['image']['name']);
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Delete old image
                if (file_exists("../" . $food['image_path'])) {
                    unlink("../" . $food['image_path']);
                }
                $image_path = "images/" . strtolower($category) . "/" . $image_name;
            } else {
                $error = "❌ Failed to upload image.";
            }
        }

        // Update DB
        if (empty($error)) {
            $update_stmt = $conn->prepare("UPDATE food_items SET name=?, description=?, price=?, category=?, image_path=? WHERE id=?");
            $update_stmt->bind_param("ssdssi", $name, $description, $price, $category, $image_path, $id);

            if ($update_stmt->execute()) {
                $success = "✅ Food item updated successfully!";
                // Refresh data
                $food = ['name'=>$name,'description'=>$description,'price'=>$price,'category'=>$category,'image_path'=>$image_path];
            } else {
                $error = "❌ Database error: " . $update_stmt->error;
            }
        }
    } else {
        $error = "❌ All fields are required and price must be numeric.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Food Item</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #f8f9fa; }
    .img-preview { width: 100px; height: 100px; object-fit: cover; }
</style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4 text-warning">Edit Food Item</h2>

    <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">Food Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($food['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category:</label>
            <select name="category" class="form-select" required>
                <option value="gallery" <?= ($food['category']=='gallery')?'selected':'' ?>>Gallery</option>
                <option value="popular" <?= ($food['category']=='popular')?'selected':'' ?>>Popular</option>
                <option value="speciality" <?= ($food['category']=='speciality')?'selected':'' ?>>Speciality</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Description:</label>
            <textarea name="description" rows="3" class="form-control" required><?= htmlspecialchars($food['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹):</label>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($food['price']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
            <img src="../<?= $food['image_path'] ?>" class="img-preview mb-2">
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave blank to keep current image</small>
        </div>

        <button type="submit" class="btn btn-warning">Update Food</button>
        <a href="manage-food.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
