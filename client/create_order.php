<?php
session_start();

// Sample data (replace with real order logic or session/cart data)
$items_total = 96;
$delivery_charge = 25;
$handling_charge = 2;
$grand_total = $items_total + $delivery_charge + $handling_charge;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Summary</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0; padding: 0;
      background: #f6f6f6;
    }

    .order-summary-box {
      width: 400px;
      margin: 50px auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 20px;
    }

    .section-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin: 6px 0;
    }

    .grand-total {
      font-weight: bold;
      font-size: 16px;
      border-top: 1px solid #ddd;
      padding-top: 10px;
      margin-top: 10px;
    }

    .note {
      background: #f2f2f2;
      padding: 10px;
      border-radius: 6px;
      font-size: 13px;
      margin-top: 15px;
    }

    .order-btn {
      margin-top: 20px;
      background: #00B14F;
      color: #fff;
      text-align: center;
      padding: 15px;
      font-size: 16px;
      border: none;
      width: 100%;
      border-radius: 8px;
      cursor: pointer;
    }

    .delivery-time {
      font-weight: bold;
      color: #00B14F;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="order-summary-box">
  <div class="delivery-time">🚚 Delivery in 10 minutes</div>

  <div class="section-title">Bill Details</div>

  <div class="info-row">
    <span>Items total</span>
    <span>₹<?= $items_total ?></span>
  </div>
  <div class="info-row">
    <span>Delivery charge</span>
    <span>₹<?= $delivery_charge ?></span>
  </div>
  <div class="info-row">
    <span>Handling charge</span>
    <span>₹<?= $handling_charge ?></span>
  </div>
  
  <div class="info-row grand-total">
    <span>Grand Total</span>
    <span>₹<?= $grand_total ?></span>
  </div>

  <div class="note">
    <strong>Cancellation Policy:</strong><br>
    Orders cannot be cancelled once packed for delivery. In case of unexpected delays, a refund will be provided, if applicable.
  </div>

  <form action="thankyou.php" method="post">
    <button class="order-btn">₹<?= $grand_total ?> - Order Now</button>
  </form>
</div>

</body>
</html>
