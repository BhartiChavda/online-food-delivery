<?php
session_start();
include 'php/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Popular Foods | Zippy</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css" />
</head>

<body>

<!-- Header Section -->
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="home" id="home">
  <div class="content">
    <h3>Food Made With Love</h3>
    <p>Lorem Ipsum Dolor Sit Amet Consectetur.</p>
  </div>
  <div class="image">
    <img src="images/home-img.png" alt="Delicious Burger" />
  </div>
</section>

<!-- Popular Foods Section -->
<section class="popular" id="popular">
  <h1 class="heading"> Most <span>Popular</span> Foods </h1>

  <div class="box-container">
    <?php
    $sql = "SELECT * FROM food_items WHERE category = 'popular'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '
        <div class="box">
          <span class="price">₹' . htmlspecialchars($row['price']) . '</span>
          <img src="' . htmlspecialchars($row['image_path']) . '" alt="">
 
          <h3>' . htmlspecialchars($row['name']) . '</h3>
          <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
          </div>
          <a href="order.php?item_id=' . $row['id'] . '" class="btn">Order Now</a>
        </div>';
      }
    } else {
      echo "<p style='text-align:center;'>No popular items found.</p>";
    }
    ?>
  </div>
</section>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>
