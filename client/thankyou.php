<?php
session_start();
include 'php/config.php';

$order = null;

if (isset($_SESSION['last_order_id'])) {
    $order_id = $_SESSION['last_order_id'];
    $result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id");

    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
    }
} else {
    echo "⚠️ No recent order found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Thank You</title>
<link rel="stylesheet" href="style.css">
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #ffecd2, #fcb69f);
        margin: 0;
        padding: 20px;
    }

    .thankyou-container {
        max-width: 800px;
        margin: auto;
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        text-align: center;
    }

    h2 {
        color: #ff6f61;
        font-size: 30px;
        margin-bottom: 10px;
    }

    p.sub-text {
        font-size: 18px;
        color: #666;
        margin-bottom: 25px;
    }

    .order-details {
        text-align: left;
        background: #fff3f3;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid #ffd6d6;
    }

    .order-details p {
        margin: 8px 0;
        font-size: 16px;
        color: #333;
    }

    h3 {
        font-size: 22px;
        color: #ff6f61;
        margin-top: 25px;
        margin-bottom: 15px;
        text-align: center;
    }

    .order-items {
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #fff8f8;
        padding: 20px;
        border-radius: 10px;
        margin-top: 15px;
        text-align: left;
        font-size: 16px;
        color: #444;
        border: 1px solid #ffe1e1;
    }

    .item-box {
        padding: 10px 12px;
        background: #fff;
        border: 1px solid #ffdcdc;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 15px;
    }

    .total-box {
        margin-top: 30px;
        background: #ffe6e6;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        color: #b23a48;
        border-radius: 10px;
    }

    .btn-group {
        margin-top: 35px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-link {
        text-decoration: none;
        padding: 14px 25px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        border-radius: 8px;
        background: linear-gradient(135deg, #ff6f61, #ff3b2e);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .btn-link:hover {
        background: linear-gradient(135deg, #ff3b2e, #e32e21);
        transform: translateY(-2px);
    }
</style>
</head>
<body>
<div class="thankyou-container">
    <h2>✅ Thank You for Your Order!</h2>
    <p class="sub-text">Your delicious food is on the way. 🎉</p>

    <!-- Order Info -->
    <div class="order-details">
        <p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>Mobile:</strong> <?= htmlspecialchars($order['mobile']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
        <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_status']) ?></p>
    </div>

    <!-- Order Items -->
    <h3>🍽️ Items You Ordered</h3>
    <div class="order-items">
        <?php
        // Convert order_details (plain text) into separate styled items
        $items = explode("\n", trim($order['order_details']));
        foreach ($items as $item) {
            echo "<div class='item-box'>" . htmlspecialchars($item) . "</div>";
        }
        ?>
    </div>

    <!-- Grand Total -->
    <div class="total-box">
        Grand Total: ₹<?= $order['total_amount'] ?>
    </div>

    <!-- Navigation Buttons -->
    <div class="btn-group">
        <a href="index.php" class="btn-link">🏠 Home</a>
        <a href="my-orders.php" class="btn-link">📦 My Orders</a>
        <a href="track-order.php?order_id=<?= $order['order_id'] ?>" class="btn-link">📍 Track Order</a>
    </div>
</div>

<script>
// ✅ Clear local cart after successful order
localStorage.removeItem('cartItems');
</script>

</body>
</html>
