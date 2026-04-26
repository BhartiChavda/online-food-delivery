<?php
include 'php/config.php'; // DB connection

// Fetch records where category is 'speciality'
$query = "SELECT * FROM food_items WHERE category = 'speciality'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Speciality</title>
    <link rel="stylesheet" href="style.css"> <!-- Your CSS file -->
</head>
<body>

<!-- Header Section -->
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="home" id="home">
    <div class="content">
        <h3>Exquisite Flavors, Just for You</h3>
        <p>Experience dishes that combine tradition, creativity, and irresistible taste.</p>
    </div>
    <div class="image">
        <img src="../images/home-img.png" alt="Delicious Burger" />
    </div>
</section>

<!-- Speciality Section -->
<section class="speciality" id="speciality">
    <h1 class="heading"> our <span>speciality</span> </h1>

    <div class="box-container">
        <?php

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Fix image path
        $imagePath = "../" . htmlspecialchars($row['image_path']); // add ../ because client folder is inside project


                echo '
                <div class="box">
                    <img class="image" src="' . $imagePath . '" alt="' . htmlspecialchars($row['name']) . '">
                    <div class="content">
                        <img src="../images/zippy.png" alt="">
                        <h3>' . htmlspecialchars($row['name']) . '</h3>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                    </div>
                </div>';
            }
        } else {
            echo "<p style='text-align:center; color:red;'>No speciality items found.</p>";
        }
        ?>
    </div>
</section>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>
