<?php
// Start session and DB connection
session_start();
$conn = new mysqli("localhost", "root", "", "online_food_delivery");

// Error check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all reviews
$reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Reviews</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Simple review styles */
    .review-section { max-width: 800px; margin: auto; padding: 20px; }
    .review-form { background: #f9f9f9; padding: 20px; margin-bottom: 30px; border-radius: 10px; }
    .review-item { background: #fff; border-left: 5px solid var(--red); padding: 15px; margin-bottom: 15px; border-radius: 8px; }
    .review-item h4 { margin: 0 0 5px; color: var(--red); }
    .stars { color: gold; }
    input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
    button { background: var(--red); color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  
  <section class="review-section">
    <h2>Leave Your Review</h2>
    <form class="review-form" action="submit_review.php" method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <select name="rating" required>
        <option value="">Select Rating</option>
        <option value="5">5 - Excellent</option>
        <option value="4">4 - Good</option>
        <option value="3">3 - Average</option>
        <option value="2">2 - Poor</option>
        <option value="1">1 - Bad</option>
      </select>
      <textarea name="comment" rows="4" placeholder="Your Review..." required></textarea>
      <button type="submit">Submit Review</button>
    </form>

    <h2>Customer Reviews</h2>
    <?php if ($reviews->num_rows > 0): ?>
      <?php while($row = $reviews->fetch_assoc()): ?>
        <div class="review-item">
          <h4><?= htmlspecialchars($row['name']) ?></h4>
          <div class="stars"><?= str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) ?></div>
          <p><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
          <small><?= $row['created_at'] ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No reviews yet. Be the first to leave one!</p>
    <?php endif; ?>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>
