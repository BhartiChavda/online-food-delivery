<?php
include '../php/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Food Gallery | Zippy</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>

<!-- Header Section -->
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="home" id="home">
    <div class="content">
        <h3>Feast Your Eyes Before Your Tastebuds</h3>
        <p>Explore the vibrant visuals of our delicious meals that make every bite irresistible.</p>
    </div>
    <div class="image">
        <img src="../images/home-img.png" alt="Delicious Burger" />
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery" id="gallery">
    <h1 class="heading"> food <span> gallery </span> </h1>
    <div class="box-container">
        <?php
        $sql = "SELECT * FROM food_items WHERE category = 'gallery'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="box">
                    <img src="../' . htmlspecialchars($row['image_path']) . '" alt="">
                    <div class="content">
                        <h3>' . htmlspecialchars($row['name']) . '</h3>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                        <p class="price">₹' . number_format($row['price'], 2) . '</p>
                        <button class="btn add-to-cart" 
                            data-id="' . $row['id'] . '" 
                            data-name="' . htmlspecialchars($row['name']) . '" 
                            data-price="' . $row['price'] . '">
                            Add to Cart
                        </button>
                    </div>
                </div>';
            }
        } else {
            echo '<p>No items found in gallery.</p>';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="../script.js"></script>

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
