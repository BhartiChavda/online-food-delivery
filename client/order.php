<?php
session_start();
include 'php/config.php';

$loggedInEmail = $_SESSION['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read input values
    $user_id       = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $fullname      = trim($_POST['fullname'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $mobile        = trim($_POST['mobile'] ?? '');
    $address       = trim($_POST['address'] ?? '');
    $order_details = trim($_POST['order_details'] ?? '');
    $total_amount  = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0.0;

    // Basic validation
    if ($fullname === '' || $email === '' || $mobile === '' || $address === '' || $order_details === '' || $total_amount <= 0) {
        $error = "Please fill all fields and ensure amount is valid.";
    } else {
        // Insert order with COD payment status
        $payment_status = "COD - Pending";
        $stmt = $conn->prepare("
            INSERT INTO orders 
                (user_id, fullname, email, mobile, address, order_details, total_amount, payment_status) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            $error = "Database error: " . $conn->error;
        } else {
            // i = integer, s = string, d = double
            $stmt->bind_param("isssssds", $user_id, $fullname, $email, $mobile, $address, $order_details, $total_amount, $payment_status);
            if ($stmt->execute()) {
                $_SESSION['last_order_id'] = $stmt->insert_id;
                header("Location: thankyou.php");
                exit();
            } else {
                $error = "Order Failed: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Place Order - Zippy Food</title>
<link rel="stylesheet" href="style.css">
<style>
body { 
  background: linear-gradient(135deg, #ff9a9e, #fad0c4); 
  font-family: 'Poppins', sans-serif; 
  margin: 0;
}
.order { 
  padding: 40px 20px; 
  max-width: 700px; 
  margin: 50px auto; 
  background: #fff; 
  box-shadow: 0 5px 25px rgba(0,0,0,0.15); 
  border-radius: 15px; 
}
.heading { 
  font-size: 30px; 
  text-align: center; 
  margin-bottom: 20px; 
  color: #ff3838; 
  font-weight: 700; 
}
.order-form { 
  display: flex; 
  flex-direction: column; 
  gap: 15px; 
}
.box { 
  padding: 14px; 
  border: 2px solid #eee; 
  border-radius: 8px; 
  font-size: 15px; 
}
.note {
  background: linear-gradient(135deg, #fffaf2, #fff4e0);
  border: 1px solid #ffd580;
  padding: 14px 18px;
  border-radius: 12px;
  font-size: 15px;
  margin-bottom: 16px;
  color: #5a3e1b;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
  position: relative;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.note::before {
  content: "💡";
  position: absolute;
  top: 12px;
  left: 12px;
  font-size: 18px;
}

.note:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}
.error {
  background: #ffe8e8;
  border: 1px solid #ffb3b3;
  color: #c00000;
  padding: 10px 12px;
  border-radius: 8px;
  margin-bottom: 10px;
  font-size: 14px;
}
</style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="order" id="order">
  <h1 class="heading">Place Your <span>Order</span></h1>

  <?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="note">
    <strong>Payment Method:</strong> Cash on Delivery (COD Only)
  </div>

  <form id="orderForm" action="order.php" method="POST" class="order-form" autocomplete="on">
    <input type="text" name="fullname" placeholder="Full Name" required class="box">
    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($loggedInEmail); ?>" <?php echo $loggedInEmail ? 'readonly' : ''; ?> class="box">
    <input type="text" name="mobile" placeholder="Mobile" required class="box" inputmode="numeric">
    <textarea name="address" placeholder="Address" required class="box"></textarea>
    <textarea name="order_details" id="order_details" placeholder="Order Details" required class="box"></textarea>
    <input type="number" id="amount" name="total_amount" placeholder="Total Amount" required class="box" readonly step="0.01" min="0">
    <input type="submit" id="submitBtn" value="Place Order (COD)" class="btn">
  </form>
</section>

<script>
// Get cart items from localStorage
function getCartItems() {
  try {
    return JSON.parse(localStorage.getItem("cartItems")) || [];
  } catch(e) {
    return [];
  }
}

// Load cart data into order form
function loadCartData() {
  const items = getCartItems();
  let total = 0;
  let details = "";
  items.forEach(item => {
    const qty = Number(item.qty) || 0;
    const price = Number(item.price) || 0;
    const lineTotal = price * qty;
    total += lineTotal;
    details += `${item.name} (x${qty}) - ₹${lineTotal}\n`;
  });
  document.getElementById("amount").value = total.toFixed(2);
  document.getElementById("order_details").value = details.trim();
}

document.addEventListener("DOMContentLoaded", loadCartData);
</script>

<?php include 'footer.php'; ?>
</body>
</html>
