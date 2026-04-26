<?php
include 'php/db.php';

if (!isset($_GET['id'])) {
    die("Order ID not provided");
}

$order_id = intval($_GET['id']);
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Order not found");
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $order['order_id']; ?></title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .invoice-box { max-width: 700px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .btn-print { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
<div class="invoice-box">
    <h2>Invoice</h2>
    <p><strong>Invoice ID:</strong> <?php echo $order['order_id']; ?></p>
    <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
    <hr>
    <h3>Billing Details</h3>
    <p><?php echo $order['fullname']; ?><br>
       <?php echo $order['email']; ?><br>
       <?php echo $order['mobile']; ?><br>
       <?php echo $order['address']; ?></p>
    <hr>
    <h3>Order Details</h3>
    <table>
        <tr class="heading">
            <td>Items</td>
            <td>Amount (₹)</td>
        </tr>
        <tr>
            <td><?php echo nl2br($order['order_details']); ?></td>
            <td><?php echo number_format($order['total_amount'], 2); ?></td>
        </tr>
    </table>
    <p><strong>Payment Status:</strong> <?php echo $order['payment_status']; ?></p>
    <p><strong>Order Status:</strong> <?php echo ucfirst($order['status']); ?></p>
    <button class="btn-print" onclick="window.print()">Print Invoice</button>
</div>
</body>
</html>
