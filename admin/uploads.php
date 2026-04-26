<?php
session_start();
include '../php/config.php';

header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Something went wrong"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $image = $_FILES['image'] ?? null;

    $allowed_categories = ['gallery', 'popular', 'speciality'];

    if (!empty($name) && !empty($price) && !empty($description) && !empty($image['name']) && in_array($category, $allowed_categories)) {

        $upload_dir = dirname(__DIR__) . "/images/" . $category . "/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($image["name"]);
        $target_file = $upload_dir . $image_name;
        $image_path = "images/" . $category . "/" . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_extensions)) {
            if ($image["size"] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($image["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO food_items (name, description, price, image_path, category) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssdss", $name, $description, $price, $image_path, $category);

                    if ($stmt->execute()) {
                        $response = ["status" => "success", "message" => "$category food item added successfully"];
                    } else {
                        unlink($target_file);
                        $response["message"] = "Database error: " . $stmt->error;
                    }
                } else {
                    $response["message"] = "Failed to upload image!";
                }
            } else {
                $response["message"] = "Image size must be under 2MB!";
            }
        } else {
            $response["message"] = "Invalid image format (only jpg, jpeg, png, gif)!";
        }
    } else {
        $response["message"] = "Please fill all fields correctly!";
    }
}

echo json_encode($response);
?>
