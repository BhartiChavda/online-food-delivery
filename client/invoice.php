<?php
session_start();
require_once "php/config.php";

// Get user ID
$user_id = $_SESSION['user']['id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Fetch cart items
$query = "SELECT * FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

$cart_items = [];
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

// After displaying invoice, delete items
$delete_query = "DELETE FROM cart WHERE user_id = $user_id";
mysqli_query($conn, $delete_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zippy Invoice</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .invoice-container {
            width: 800px;
            margin: 40px auto;
            border: 1px solid #ccc;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .invoice-header img {
            width: 150px;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .thank-you {
            margin-top: 40px;
            text-align: center;
            font-size: 1.2em;
            color: green;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header">
        <h2>Invoice</h2>
        <img src="images/logo.png" alt="Zippy Logo">
    </div>

    <table>
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['food_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>
                <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">Total: ₹<?= number_format($total, 2) ?></p>

    <p class="thank-you">✅ Thank you for your order! Your cart is now empty.</p>
</div>

</body>
</html>
