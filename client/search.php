<?php
session_start();
include 'php/config.php'; // DB Connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zippy | Search Results</title>
<link rel="stylesheet" href="style.css">
<style>
/* Search Section */
.search-container {
  display: flex;
  justify-content: center;
  margin: 100px 0 30px;
  gap: 10px;
}
.search-container input {
  padding: 10px;
  width: 280px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 16px;
}
.search-container button {
  padding: 10px 15px;
  background: #e74c3c;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}
.search-container button:hover { background: #c0392b; }

/* Search Results */
.search-results {
  max-width: 1200px;
  margin: auto;
  padding: 20px;
}
.search-title {
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 30px;
}
.results-grid {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
}
.food-card {
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 15px;
  background: #fff;
  text-align: center;
  transition: 0.3s;
  width: 250px;
}
.food-card:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.food-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 8px;
}
.food-card h3 { margin: 10px 0 5px; font-size: 20px; }
.food-card p { font-size: 14px; color: #666; margin-bottom: 10px; }
.price { color: green; font-weight: bold; font-size: 18px; }
.order-btn {
  background: #e74c3c;
  color: #fff;
  border: none;
  padding: 8px 15px;
  margin-top: 10px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: 0.3s;
}
.order-btn:hover { background: #c0392b; }
</style>
</head>
<body>

<!-- Header -->
<?php include 'header.php'; ?>

<!-- Search Bar -->
<div class="search-container">
  <form action="search.php" method="GET">
    <input type="text" name="query" placeholder="Search more food..."
           value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>" required>
    <button type="submit"><i class="fas fa-search"></i></button>
  </form>
</div>

<!-- Search Results -->
<div class="search-results">
<?php
if (isset($_GET['query'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['query']);
    echo "<h2 class='search-title'>Search Results for: <i>" . htmlspecialchars($searchTerm) . "</i></h2>";

    // ✅ Only Popular & Gallery items allowed (Speciality excluded)
    $query = "SELECT * FROM food_items 
              WHERE (name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%')
              AND category IN ('popular', 'gallery')";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='results-grid'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $name = htmlspecialchars($row['name'] ?? 'Unknown Item');
            $description = htmlspecialchars($row['description'] ?? '');
            $price = $row['price'] ?? '0.00';
            $id = $row['id'];

            // Image path from DB
            $image = $row['image_path'] ?? '';
            if (!empty($image)) {
                $image = "../" . $image;
            } else {
                $image = "../images/default.jpg";
            }

            echo "
            <div class='food-card'>
                <img src='{$image}' alt='{$name}'>
                <h3>{$name}</h3>
                <p>{$description}</p>
                <div class='price'>₹{$price}</div>
                <button class='order-btn'
                    onclick=\"addToCart('{$id}', '{$name}', '{$price}', '{$image}')\">
                    Add to Cart
                </button>
            </div>
            ";
        }
        echo "</div>";
    } else {
        echo "<p style='text-align:center; color:red;'>No results found for '{$searchTerm}'</p>";
    }
}
?>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script>
function getCartItems() {
    return JSON.parse(localStorage.getItem("cartItems")) || [];
}
function setCartItems(items) {
    localStorage.setItem("cartItems", JSON.stringify(items));
}
function updateCartBadge() {
    const items = getCartItems();
    const totalQty = items.reduce((sum, i) => sum + i.qty, 0);
    document.getElementById("cart-count").textContent = totalQty;
}
function addToCart(id, name, price, image) {
    let cart = getCartItems();
    let existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({id, name, price: parseFloat(price), image, qty: 1});
    }
    setCartItems(cart);
    updateCartBadge();
    alert(name + " added to cart!");
}
document.addEventListener("DOMContentLoaded", updateCartBadge);
</script>

</body>
</html>
