<?php
require_once __DIR__ . '/con.php';

// Validate order ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid order ID.');
}

$order_id = (int)$_GET['id'];

// Fetch order details
$orderSQL = "
    SELECT o.order_id, 
           o.total_amount, 
           o.payment_status, 
           o.order_date,
           COALESCE(u.name, 'Guest') AS customer_name,
           u.email AS customer_email,
           u.mobile AS customer_mobile,
           t.status AS tracking_status,
           t.updated_at AS tracking_updated
    FROM orders o
    LEFT JOIN users u ON u.id = o.user_id OR u.email = o.email
    LEFT JOIN order_tracking t ON t.order_id = o.order_id
    WHERE o.order_id = ?
    LIMIT 1
";
$stmt = $con->prepare($orderSQL);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}

// Fetch ordered items
$itemsSQL = "
    SELECT item_name, quantity, price 
    FROM order_items 
    WHERE order_id = ?
";
$itemStmt = $con->prepare($itemsSQL);
$itemStmt->bind_param("i", $order_id);
$itemStmt->execute();
$items = $itemStmt->get_result();

include __DIR__ . '/admin-header.php';
?>

<div class="container-fluid px-3 px-md-4">
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h4 class="mb-3">Order #<?= htmlspecialchars($order['order_id']); ?></h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="text-muted mb-2">Customer Details</h6>
                        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($order['customer_email'] ?: 'N/A'); ?></p>
                        <p class="mb-1"><strong>Mobile:</strong> <?= htmlspecialchars($order['customer_mobile'] ?: 'N/A'); ?></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="text-muted mb-2">Order Details</h6>
                        <p class="mb-1"><strong>Status:</strong> <?= htmlspecialchars($order['payment_status']); ?></p>
                        <p class="mb-1"><strong>Total Amount:</strong> ₹<?= number_format((float)$order['total_amount'], 2); ?></p>
                        <p class="mb-1"><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['order_date'])); ?></p>
                        <p class="mb-1"><strong>Tracking:</strong> <?= $order['tracking_status'] ? htmlspecialchars($order['tracking_status']) : '—'; ?></p>
                        <?php if($order['tracking_updated']): ?>
                            <p class="mb-0 text-muted small">(Updated: <?= date('d M Y, h:i A', strtotime($order['tracking_updated'])); ?>)</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ordered Items -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="mb-3">Ordered Items</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
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
                        while ($item = $items->fetch_assoc()): 
                            $subtotal = $item['quantity'] * $item['price'];
                            $grandTotal += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item_name']); ?></td>
                            <td class="text-center"><?= (int)$item['quantity']; ?></td>
                            <td class="text-end"><?= number_format((float)$item['price'], 2); ?></td>
                            <td class="text-end"><?= number_format($subtotal, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                            <td class="text-end"><strong>₹<?= number_format($grandTotal, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end">
        <a href="invoice.php?id=<?= $order['order_id']; ?>" class="btn btn-secondary me-2">Download Invoice</a>
        <a href="reports.php" class="btn btn-outline-primary">Back to Reports</a>
    </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>
