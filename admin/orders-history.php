<?php
session_start();
include 'admin-header.php';
include '../php/config.php';

// ✅ Auto Complete Orders (based on ETA time)
$conn->query("
    UPDATE orders 
    SET status='completed' 
    WHERE status='assigned' 
      AND confirm_time IS NOT NULL 
      AND TIMESTAMPADD(MINUTE, estimated_time, confirm_time) <= NOW()
");

// ✅ Handle Assign / Complete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = intval($_POST['order_id']);
    if (isset($_POST['assign_order'])) {
        $deliveryBoyId = intval($_POST['delivery_boy_id']);
        $eta = intval($_POST['eta']);
        $stmt = $conn->prepare("UPDATE orders SET delivery_boy_id=?, status='assigned', estimated_time=?, confirm_time=NOW() WHERE order_id=? AND status!='completed'");
        $stmt->bind_param("iii", $deliveryBoyId, $eta, $orderId);
        $stmt->execute();
    }
    if (isset($_POST['complete_order'])) {
        $stmt = $conn->prepare("UPDATE orders SET status='completed' WHERE order_id=?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
    }
    header("Location: orders-history.php");
    exit();
}

// ✅ Fetch Orders
$result = $conn->query("
SELECT o.order_id,o.fullname,o.email,o.mobile,o.address,o.total_amount,o.status,o.order_date,o.estimated_time,
d.id AS delivery_id,d.name AS delivery_name,d.mobile AS delivery_mobile
FROM orders o
LEFT JOIN delivery_boys d ON o.delivery_boy_id=d.id
ORDER BY o.order_id DESC
");

// ✅ Fetch Delivery Boys
$deliveryBoys = $conn->query("SELECT id,name,mobile FROM delivery_boys ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Orders History & Assign Delivery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script>
function confirmComplete(){ return confirm("Are you sure you want to mark this order as completed?"); }
</script>
<style>
body { background:#f4f6f9; font-family:'Poppins',sans-serif; }
.container { max-width:1200px; margin:30px auto; }
h3 { margin-bottom:25px; color:#e04f56; font-weight:bold; }
</style>
</head>
<body>
<div class="container">
<h3>📋 Orders History & Assign Delivery</h3>

<div class="table-responsive">
<table class="table table-bordered table-hover align-middle">
<thead class="table-light">
<tr>
<th>ID</th>
<th>Customer</th>
<th>Address</th>
<th>Total (₹)</th>
<th>Status</th>
<th>Delivery Boy</th>
<th>Assign / Update</th>
<th>Complete</th>
<th>Date</th>
</tr>
</thead>
<tbody>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?= $row['order_id'] ?></td>
<td>
<strong><?= htmlspecialchars($row['fullname']) ?></strong><br>
<span class="text-muted small"><?= htmlspecialchars($row['email']) ?></span><br>
<span class="text-muted small">📞 <?= htmlspecialchars($row['mobile']) ?></span>
</td>
<td><?= htmlspecialchars($row['address']) ?></td>
<td><?= number_format($row['total_amount'],2) ?></td>
<td>
<span class="badge 
<?= $row['status']=='pending'?'bg-warning text-dark':
   ($row['status']=='assigned'?'bg-primary text-white':
   ($row['status']=='completed'?'bg-success text-white':'bg-danger text-white')) ?>">
<?= ucfirst($row['status'] ?: 'pending') ?>
<?php if($row['status']=='assigned' && $row['estimated_time']>0): ?>(ETA: <?= intval($row['estimated_time']) ?> min)<?php endif; ?>
</span>
</td>
<td><?= $row['delivery_name'] ? htmlspecialchars($row['delivery_name']).' (📞 '.$row['delivery_mobile'].')' : '—' ?></td>
<td>
<?php if($row['status']!='completed'): ?>
<form method="POST" class="d-flex align-items-center">
<input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
<select name="delivery_boy_id" class="form-select form-select-sm me-2" required>
<option value="">Select Delivery</option>
<?php foreach($deliveryBoys as $boy): ?>
<option value="<?= $boy['id'] ?>" <?= $row['delivery_id']==$boy['id']?'selected':'' ?>><?= htmlspecialchars($boy['name']).' ('.$boy['mobile'].')' ?></option>
<?php endforeach; ?>
</select>
<input type="number" name="eta" class="form-control form-control-sm me-2" value="<?= $row['estimated_time'] ?: '' ?>" placeholder="ETA (min)" required>
<button type="submit" name="assign_order" class="btn btn-sm btn-success">Assign</button>
</form>
<?php else: ?>
<span class="text-muted">—</span>
<?php endif; ?>
</td>
<td>
<?php if($row['status']!='completed'): ?>
<form method="POST" onsubmit="return confirmComplete();">
<input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
<button type="submit" name="complete_order" class="btn btn-sm btn-success">Complete</button>
</form>
<?php else: ?><span class="text-success fw-bold">✓ Completed</span><?php endif; ?>
</td>
<td><?= date("d M Y, h:i A", strtotime($row['order_date'])) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
