<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user']['email'];

if (!isset($_GET['order_id'])) {
    echo "⚠️ Order not specified.";
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order for this user
$sql = "SELECT * FROM orders WHERE order_id = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $order_id, $user_email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo "❌ Order not found.";
    exit;
}

$order = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details - Zippy Food</title>
<link rel="stylesheet" href="style.css">
<style>
body {
    background: linear-gradient(135deg, #ffecd2, #fcb69f);
    font-family: 'Poppins', sans-serif;
    margin: 0;
    color: #333;
}
.container {
    max-width: 850px;
    margin: 50px auto;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 6px 28px rgba(0,0,0,0.15);
    padding: 40px;
}
h2 { 
    text-align: center; 
    color: #ff3838; 
    margin-bottom: 25px; 
    font-size: 28px;
}
h3 {
    margin-top: 30px;
    font-size: 22px;
    color: #444;
}
.order-info p { 
    margin: 10px 0; 
    font-size: 17px; 
}
.table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 20px; 
    font-size: 16px;
}
.table th, .table td { 
    border: 1px solid #eee; 
    padding: 12px; 
    text-align: left; 
}
.table th { 
    background: #ff3838; 
    color: #fff; 
    font-size: 16px; 
}
.total { 
    font-weight: bold; 
    font-size: 18px; 
    text-align: right; 
}
.status { 
    padding: 8px 15px; 
    border-radius: 25px; 
    font-size: 14px; 
    color: #fff; 
}
.status.pending { background: #f0ad4e; }
.status.completed { background: #28a745; }
.status.cancelled { background: #dc3545; }

/* ✅ Common Button Design */
.btn-custom {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 12px 28px;
    font-size: 18px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
    margin-top: 25px;
}
.btn-custom:hover {
    background-color: #d32f2f;
}
</style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
  <h2>Order Details</h2>
  
  <div class="order-info">
    <p><strong>Order ID:</strong> #<?= $order['order_id']; ?></p>
    <p><strong>Date:</strong> <?= date("d M Y, h:i A", strtotime($order['order_date'])); ?></p>
    <p><strong>Name:</strong> <?= htmlspecialchars($order['fullname']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
    <p><strong>Mobile:</strong> <?= htmlspecialchars($order['mobile']); ?></p>
    <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])); ?></p>
    <p><strong>Status:</strong> 
      <span class="status <?= $order['status']; ?>"><?= ucfirst($order['status']); ?></span>
    </p>
    <p><strong>Payment:</strong> <?= htmlspecialchars($order['payment_status']); ?></p>
  </div>

  <h3>Items Ordered</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Price (₹)</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $grandTotal = 0;
    $orderDetails = $order['order_details'];

    if (!empty($orderDetails)) {
        // Split each item by newline
        $items = preg_split("/\r\n|\n|\r/", trim($orderDetails));
        foreach ($items as $line) {
            if (empty(trim($line))) continue;

            // Match pattern: Item Name (xQty) - ₹Price
            if (preg_match('/^(.*?) \(x(\d+)\) - ₹(\d+(?:\.\d+)?)/', $line, $matches)) {
                $itemName = trim($matches[1]);
                $qty = intval($matches[2]);
                $price = floatval($matches[3]);
                $subtotal = $qty * $price;
                $grandTotal += $subtotal;
            } else {
                $itemName = htmlspecialchars($line);
                $qty = 1;
                $price = 0;
                $subtotal = 0;
            }

            echo "<tr>
                    <td>".htmlspecialchars($itemName)."</td>
                    <td>".htmlspecialchars($qty)."</td>
                    <td>₹".number_format($price, 2)."</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No items found.</td></tr>";
    }
    ?>
    <tr>
      <td colspan="2" class="total">Total Amount</td>
      <td class="total">₹<?= number_format($order['total_amount'], 2); ?></td>
    </tr>
    </tbody>
  </table>

  <a href="my-orders.php" class="btn-custom">⬅ Back to Order History</a>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
