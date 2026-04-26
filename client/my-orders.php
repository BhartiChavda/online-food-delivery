<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user']['email'];

// Fetch latest 50 orders for the logged-in user
$query = "SELECT * FROM orders WHERE email = ? ORDER BY order_id DESC LIMIT 50";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders | Zippy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { 
    background: linear-gradient(135deg, #ffecd2, #fcb69f); 
    font-family: 'Poppins', sans-serif; 
}
.navbar { background: #e04f56; }
.navbar-brand, .nav-link { color: #fff !important; font-weight: 500; }
.nav-link:hover { color: #f1f1f1 !important; }
.container { margin-top: 40px; margin-bottom: 40px; }
.header-bar { 
    background: #e04f56; 
    color: #fff; 
    padding: 18px; 
    text-align: center; 
    font-size: 26px; 
    font-weight: bold; 
    border-radius: 10px; 
    margin-bottom: 25px; 
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.card { 
    border-radius: 12px; 
    box-shadow: 0 6px 18px rgba(0,0,0,0.1); 
    transition: 0.3s ease-in-out; 
}
.card:hover { transform: translateY(-5px); }
.card-body { padding: 25px; }
.card-body h5 { font-size: 20px; font-weight: bold; color: #e04f56; }
.card-body p { font-size: 16px; margin: 5px 0; }
.status { 
    font-weight: bold; 
    padding: 6px 12px; 
    border-radius: 25px; 
    font-size: 13px; 
    margin-left: 8px; 
}
.status-pending { background: #ffc107; color: #000; }
.status-success { background: #28a745; color: #fff; }
.status-cancelled { background: #dc3545; color: #fff; }

/* ✅ Button Style */
.track-btn {
    background-color: #f44336;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: 0.3s;
    display: inline-block;
    margin-top: 12px;
    margin-right: 8px;
}
.track-btn:hover { 
    background-color: #d32f2f; 
    color: #fff; 
    text-decoration: none;
}
</style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">Zippy</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="my-orders.php">My Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Orders Section -->
<div class="container">
    <div class="header-bar">📦 My Orders</div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($order = $result->fetch_assoc()):
            // Decode order items
            $items = json_decode($order['order_details'], true);
            $itemList = [];
            if (is_array($items)) {
                foreach ($items as $item) {
                    $itemList[] = $item['name'] . ' ×' . $item['qty'];
                }
            }
        ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5>
                        Order #<?= htmlspecialchars($order['order_id']) ?>
                        <span class="status <?= ($order['payment_status'] == 'Paid') ? 'status-success' : 'status-pending' ?>">
                            <?= htmlspecialchars($order['payment_status']) ?>
                        </span>
                        <span class="status 
                            <?= ($order['status'] == 'completed') ? 'status-success' : (($order['status'] == 'cancelled') ? 'status-cancelled' : 'status-pending') ?>">
                            <?= ucfirst($order['status']); ?>
                        </span>
                    </h5>
                    <p><strong>Order Date:</strong> <?= date("d M Y, h:i A", strtotime($order['order_date'])) ?></p>
                    <p><strong>Total Amount:</strong> ₹<?= htmlspecialchars($order['total_amount']) ?></p>
                    <p><strong>Items:</strong> <?= !empty($itemList) ? implode(', ', $itemList) : htmlspecialchars($order['order_details']); ?></p>

                    <!-- ✅ Added both buttons -->
                    <a href="order-details.php?order_id=<?= $order['order_id'] ?>" class="track-btn">View Details</a>
                    <a href="track-order.php?order_id=<?= $order['order_id'] ?>" class="track-btn">Track Order</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">🚫 You have not placed any orders yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
