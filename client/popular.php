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
    <h3>Customer Favorites You Can’t Miss</h3>
    <p>From classic delights to trending dishes, enjoy what everyone is raving about.</p>
  </div>
  <div class="image">
    <img src="../images/home-img.png" alt="Delicious Burger" />
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
        $imagePath = "../" . htmlspecialchars($row['image_path']);
        echo '
        <div class="box">
          <span class="price">₹' . htmlspecialchars($row['price']) . '</span>
          <img src="' . $imagePath . '" alt="' . htmlspecialchars($row['name']) . '">
          <h3>' . htmlspecialchars($row['name']) . '</h3>
          <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
          </div>
          <button class="btn add-to-cart"
            data-id="' . $row['id'] . '"
            data-name="' . htmlspecialchars($row['name']) . '"
            data-price="' . $row['price'] . '">
            Add to Cart
          </button>
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

<!-- Add to Cart Logic -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", () => {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const price = parseFloat(button.dataset.price);

            let cart = JSON.parse(localStorage.getItem("cartItems")) || [];
            const existing = cart.find(item => item.id === id);

            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }

            localStorage.setItem("cartItems", JSON.stringify(cart));
            alert(`${name} added to cart!`);
        });
    });
});
</script>

</body>
</html>
