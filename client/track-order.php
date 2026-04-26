<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user']['email'];
$orderId = intval($_GET['order_id'] ?? 0);
if ($orderId <= 0) die("Invalid order.");

// AUTO COMPLETE ORDERS
$conn->query("
    UPDATE orders 
    SET status='completed' 
    WHERE status='assigned' 
      AND confirm_time IS NOT NULL 
      AND TIMESTAMPADD(MINUTE, estimated_time, confirm_time) <= NOW()
");

// AJAX request for live status
if (isset($_GET['check_status']) && $_GET['check_status'] == 1) {
    $stmt = $conn->prepare("SELECT status FROM orders WHERE order_id=? AND email=?");
    $stmt->bind_param("is", $orderId, $user_email);
    $stmt->execute();
    $orderStatus = $stmt->get_result()->fetch_assoc();
    echo $orderStatus['status'] ?? 'unknown';
    exit;
}

// Fetch order + delivery boy
$stmt = $conn->prepare("
SELECT o.*, d.name AS delivery_name, d.mobile AS delivery_mobile
FROM orders o
LEFT JOIN delivery_boys d ON o.delivery_boy_id=d.id
WHERE o.order_id=? AND o.email=?");
$stmt->bind_param("is", $orderId, $user_email);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) die("Order not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Track Order #<?= $order['order_id'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#fdf6f6; font-family:'Poppins',sans-serif; }
.container { max-width:850px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 5px 18px rgba(0,0,0,0.1);}
h2 { color:#e04f56; margin-bottom:20px; }
.table td, .table th { vertical-align: middle; }
.btn-custom { 
    background-color: #e04f56; 
    color: #fff; 
    border: none; 
    padding: 10px 20px; 
    border-radius: 6px; 
    text-decoration: none; 
    margin-right: 10px;
    display: inline-block;
}
.btn-custom:hover { background-color: #c0392b; color: #fff; }
</style>
</head>
<body>
<div class="container">
<h2>📍 Track Order #<?= $order['order_id'] ?></h2>

<p><strong>Placed On:</strong> <?= date("d M Y, h:i A", strtotime($order['order_date'])) ?></p>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['fullname']) ?> (<?= htmlspecialchars($order['email']) ?>)</p>
<p><strong>Mobile:</strong> <?= htmlspecialchars($order['mobile']) ?></p>
<p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>

<h5 class="mt-4">Order Items</h5>
<table class="table table-bordered">
<thead class="table-light">
<tr>
<th>Item</th>
<th class="text-center">Qty</th>
<th class="text-end">Price (₹)</th>
<th class="text-end">Subtotal (₹)</th>
</tr>
</thead>
<tbody>
<?php
$grandTotal = 0;
$orderDetails = $order['order_details'];

if (!empty($orderDetails)) {
    // Split by newline (each line = "Item (xQty) - ₹Price")
    $items = preg_split("/\r\n|\n|\r/", trim($orderDetails));

    foreach ($items as $itemLine) {
        if (empty(trim($itemLine))) continue;

        // Match pattern "Name (xQty) - ₹Price"
        if (preg_match('/^(.*?) \(x(\d+)\) - ₹(\d+)/', $itemLine, $matches)) {
            $itemName = trim($matches[1]);
            $qty = (int)$matches[2];
            $price = (float)$matches[3];
            $subtotal = $qty * $price;
        } else {
            $itemName = htmlspecialchars($itemLine);
            $qty = 1;
            $price = (float)$order['total_amount'];
            $subtotal = $price;
        }

        $grandTotal += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($itemName) ?></td>
            <td class="text-center"><?= $qty ?></td>
            <td class="text-end"><?= number_format($price, 2) ?></td>
            <td class="text-end"><?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php
    }
}
?>
<tr class="table-light">
    <th colspan="3" class="text-end">Total</th>
    <th class="text-end">₹<?= number_format($grandTotal, 2) ?></th>
</tr>
</tbody>
</table>

<h5 class="mt-4">Order Status</h5>
<p><strong>Status:</strong> <span id="order-status"><?= ucfirst($order['status']) ?></span></p>

<?php if (!empty($order['delivery_name'])): ?>
<p><strong>Delivery Boy:</strong> <?= htmlspecialchars($order['delivery_name']) ?> (📞 <?= htmlspecialchars($order['delivery_mobile']) ?>)</p>
<?php endif; ?>

<!-- ✅ Home & Back Buttons -->
<a href="index.php" class="btn-custom">🏠 Home</a>
<a href="my-orders.php" class="btn-custom">⬅ Back to Orders</a>

</div>

<script>
// Auto refresh order status every 10 sec
setInterval(() => {
    fetch("track-order.php?order_id=<?= $orderId ?>&check_status=1")
        .then(res => res.text())
        .then(status => {
            document.getElementById("order-status").innerText = status.charAt(0).toUpperCase() + status.slice(1);
        });
}, 10000);
</script>
</body>
</html>
