 
<?php
$pageTitle = "Assign Delivery";
include 'admin-header.php';
include '../php/config.php';

// ------------------
// Handle assignment
// ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_order'])) {
    $orderId = intval($_POST['order_id']);
    $deliveryBoyId = intval($_POST['delivery_boy_id']);
    $eta = intval($_POST['eta']);

    $stmt = $conn->prepare("
        UPDATE orders 
        SET delivery_boy_id=?, status='assigned', estimated_time=?, confirm_time=NOW() 
        WHERE order_id=?");
    $stmt->bind_param("iii", $deliveryBoyId, $eta, $orderId);
    $stmt->execute();

    $success = "Order #$orderId assigned successfully ✅";
}

// ------------------
// Fetch unassigned / pending orders
// ------------------
$sql = "SELECT 
            o.order_id, o.fullname, o.email, o.mobile, o.address, 
            o.total_amount, o.order_date
        FROM orders o
        WHERE o.status IS NULL OR o.status='' OR o.status='pending'
        ORDER BY o.order_date ASC";

$result = $conn->query($sql);

// ------------------
// Fetch delivery boys
// ------------------
$deliveryBoys = $conn->query("SELECT id, name, mobile FROM delivery_boys WHERE status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

?>
<div class="container-fluid px-3 px-md-4">
    <h3 class="mb-3">Assign Delivery Boys</h3>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Total (₹)</th>
                    <th>Date</th>
                    <th>Assign Delivery Boy</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($row['fullname']) ?></strong><br>
                                <span class="text-muted small"><?= htmlspecialchars($row['email']) ?></span><br>
                                <span class="text-muted small">📞 <?= htmlspecialchars($row['mobile']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= number_format($row['total_amount'], 2) ?></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['order_date'])) ?></td>
                            <td>
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                    <select name="delivery_boy_id" class="form-select form-select-sm me-2" required>
                                        <option value="">Select Delivery</option>
                                        <?php foreach ($deliveryBoys as $boy): ?>
                                            <option value="<?= $boy['id'] ?>">
                                                <?= htmlspecialchars($boy['name']).' ('.$boy['mobile'].')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="eta" class="form-control form-control-sm me-2" placeholder="ETA (min)" min="5" required>
                                    <button type="submit" name="assign_order" class="btn btn-sm btn-success">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No pending orders 🎉</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'admin-footer.php'; ?>
