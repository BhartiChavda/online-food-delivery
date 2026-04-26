<?php
// admin/customers.php
require_once __DIR__ . '/con1.php';

// Search filter
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// KPIs: Total Customers, Total Orders, Total Revenue
$kpi = [
    'total_customers' => 0,
    'total_orders'    => 0,
    'total_revenue'   => 0.00
];

$res = $con->query("
    SELECT 
        (SELECT COUNT(*) FROM users) AS total_customers,
        (SELECT COUNT(*) FROM orders) AS total_orders,
        (SELECT COALESCE(SUM(total_amount),0) FROM orders) AS total_revenue
");
if ($res) {
    $kpi = $res->fetch_assoc();
}

// Main SQL for customers list
$sql = "
SELECT 
    u.id,
    u.name,
    u.email,
    u.mobile,
    COUNT(o.order_id) AS orders_count,
    COALESCE(SUM(o.total_amount),0) AS total_spent,
    MAX(o.order_date) AS last_order
FROM users u
LEFT JOIN orders o ON o.user_id = u.id
WHERE (? = '' OR u.name LIKE CONCAT('%', ?, '%')
              OR u.email LIKE CONCAT('%', ?, '%')
              OR u.mobile LIKE CONCAT('%', ?, '%'))
GROUP BY u.id, u.name, u.email, u.mobile
ORDER BY (MAX(o.order_date) IS NULL), MAX(o.order_date) DESC, u.name ASC
";

$stmt = $con->prepare($sql);
$stmt->bind_param('ssss', $q, $q, $q, $q);
$stmt->execute();
$rows = $stmt->get_result();

include __DIR__ . '/admin-header.php';
?>

<div class="container-fluid px-3 px-md-4">
  <!-- KPI Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center">
          <div class="text-muted small">Total Customers</div>
          <div class="h4 mb-0"><?= number_format((int)$kpi['total_customers']); ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center">
          <div class="text-muted small">Total Orders</div>
          <div class="h4 mb-0"><?= number_format((int)$kpi['total_orders']); ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center">
          <div class="text-muted small">Total Revenue (₹)</div>
          <div class="h4 mb-0"><?= number_format((float)$kpi['total_revenue'], 2); ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customers Table -->
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <h5 class="mb-2 mb-md-0">Customers</h5>
        <form class="d-flex" method="get" action="">
          <input class="form-control me-2" type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search name / email / mobile">
          <button class="btn btn-primary">Search</button>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name / Email</th>
              <th>Mobile</th>
              <th class="text-center">Orders</th>
              <th class="text-end">Total Spent (₹)</th>
              <th>Last Order</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; while($r = $rows->fetch_assoc()): ?>
              <tr>
                <td><?= $i++; ?></td>
                <td>
                  <div class="fw-semibold"><?= htmlspecialchars($r['name'] ?: '—'); ?></div>
                  <div class="text-muted small"><?= htmlspecialchars($r['email'] ?: '—'); ?></div>
                </td>
                <td><?= htmlspecialchars($r['mobile'] ?: '—'); ?></td>
                <td class="text-center"><?= (int)$r['orders_count']; ?></td>
                <td class="text-end"><?= number_format((float)$r['total_spent'], 2); ?></td>
                <td><?= $r['last_order'] ? date('d M Y, h:i A', strtotime($r['last_order'])) : '—'; ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if ($rows->num_rows === 0): ?>
              <tr><td colspan="6" class="text-center text-muted">No customers found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>
