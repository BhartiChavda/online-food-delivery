<?php
session_start();
include 'php/config.php';

$user_id = $_SESSION['user_id'] ?? 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $order_details = trim($_POST['order_details']);
    $total_amount = floatval($_POST['total_amount']);
    $latitude = floatval($_POST['latitude'] ?? 0);
    $longitude = floatval($_POST['longitude'] ?? 0);

    if ($fullname && $email && $mobile && $address && $order_details && $total_amount > 0 && $latitude && $longitude) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, email, mobile, address, order_details, total_amount, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississsdd", $user_id, $fullname, $email, $mobile, $address, $order_details, $total_amount, $latitude, $longitude);

        if ($stmt->execute()) {
            $_SESSION['last_order_id'] = $stmt->insert_id;
            header("Location: thankyou.php");
            exit();
        } else {
            $message = "❌ Failed to place order: " . $stmt->error;
        }
    } else {
        $message = "❌ Please fill all fields and allow location access!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Place Order</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
.order-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #333; margin-bottom: 25px; }
label { display: block; margin-top: 15px; color: #333; }
input, textarea { width: 100%; padding: 10px 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
textarea { resize: vertical; min-height: 80px; }
.btn { display: block; width: 100%; background: #ff3838; color: white; border: none; padding: 12px; margin-top: 25px; font-size: 18px; border-radius: 6px; cursor: pointer; transition: background 0.3s ease; }
.btn:hover { background: #e02e2e; }
.message { margin-top: 10px; text-align: center; font-weight: bold; color: red; }
</style>
</head>
<body>

<div class="order-container">
<h2>Place Your Order</h2>
<?php if($message) echo "<p class='message'>$message</p>"; ?>
<form action="place_order.php" method="POST" onsubmit="return validateForm();">
    <label for="fullname">Full Name</label>
    <input type="text" name="fullname" id="fullname" required>

    <label for="email">Email Address</label>
    <input type="email" name="email" id="email" required>

    <label for="mobile">Mobile Number</label>
    <input type="text" name="mobile" id="mobile" required pattern="[0-9]{10}" title="Enter a valid 10-digit mobile number">

    <label for="address">Delivery Address</label>
    <textarea name="address" id="address" required></textarea>

    <label for="order_details">Order Details</label>
    <textarea name="order_details" id="order_details" required></textarea>

    <label for="total_amount">Total Amount (₹)</label>
    <input type="number" name="total_amount" id="total_amount" required min="1">

    <!-- Hidden fields for latitude & longitude -->
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">

    <button type="submit" class="btn">Submit Order</button>
</form>
</div>

<script>
// Get customer location automatically
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
    }, function(error) {
        console.log("⚠️ Location not available: ", error.message);
        alert("Please allow location access to track your order on the map!");
    });
}

function validateForm() {
    var lat = document.getElementById('latitude').value;
    var lng = document.getElementById('longitude').value;
    var total = document.getElementById('total_amount').value;

    if (!lat || !lng) {
        alert("Please allow location access to place the order!");
        return false;
    }
    if (total <= 0) {
        alert("Total amount must be greater than 0.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
