<?php
require_once __DIR__ . '/../php/config.php';
include 'admin-header.php';

// -----------------
// Date Filter Logic
// -----------------
$where = "";
$params = [];
$types = "";

// If date filter applied via GET
if (isset($_GET['from'], $_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where = "WHERE DATE(order_date) BETWEEN ? AND ?";
    $params = [$from, $to];
    $types = "ss";
} else {
    // Default: last 30 days
    $from = date('Y-m-d', strtotime('-30 days'));
    $to = date('Y-m-d');
    $where = "WHERE DATE(order_date) BETWEEN ? AND ?";
    $params = [$from, $to];
    $types = "ss";
}

// -----------------
// Fetch Orders
// -----------------
$sql = "SELECT * FROM orders $where ORDER BY order_id DESC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// -----------------
// Prepare Chart & Table Data
// -----------------
$chartData = [];
$rows = [];

// Collect totals by date
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dateKey = date("Y-m-d", strtotime($row['order_date'])); 
        $amount = (float)$row['total_amount'];

        if (!isset($chartData[$dateKey])) {
            $chartData[$dateKey] = 0;
        }
        $chartData[$dateKey] += $amount;

        $rows[] = $row;
    }
}

// Generate all dates in range (fill missing with 0)
$labels = [];
$values = [];
$period = new DatePeriod(
    new DateTime($from),
    new DateInterval('P1D'),
    (new DateTime($to))->modify('+1 day')
);

foreach ($period as $date) {
    $dKey = $date->format("Y-m-d");
    $labels[] = $date->format("d-m"); // 👈 only day-month (no year)
    $values[] = isset($chartData[$dKey]) ? $chartData[$dKey] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders Report</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container-fluid px-3 px-md-4">

  <!-- Back Button -->
  <a href="index.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

  <!-- Filter Card -->
  <div class="card shadow-sm border-0 mb-4">
      <div class="card-header">
          <h4 class="mb-0">Filter Orders by Date</h4>
      </div>
      <div class="card-body">
          <form method="GET" class="row g-3">
              <div class="col-md-4">
                  <label class="form-label">From Date</label>
                  <input type="date" name="from" value="<?= htmlspecialchars($from) ?>" class="form-control">
              </div>
              <div class="col-md-4">
                  <label class="form-label">To Date</label>
                  <input type="date" name="to" value="<?= htmlspecialchars($to) ?>" class="form-control">
              </div>
              <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-danger me-2">Filter</button>
                  <a href="reports.php" class="btn btn-secondary">Reset</a>
              </div>
          </form>
      </div>
  </div>

  <!-- Chart Card -->
  <div class="card shadow-sm border-0 mb-4">
      <div class="card-header">
          <h4 class="mb-0">Orders Amount (Area Chart)</h4>
      </div>
      <div class="card-body">
          <canvas id="ordersChart" height="100"></canvas>
      </div>
  </div>

  <!-- Orders Table -->
  <div class="card shadow-sm border-0">
      <div class="card-header">
          <h4 class="mb-0">Orders Report</h4>
      </div>
      <div class="card-body p-3">
          <div class="table-responsive">
              <table class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>Order ID</th>
                          <th>Customer Name</th>
                          <th>Email</th>
                          <th>Mobile</th>
                          <th>Total Amount (₹)</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th>Invoice</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($rows)): ?>
                          <?php foreach ($rows as $row): ?>
                              <tr>
                                  <td><?= $row['order_id']; ?></td>
                                  <td><?= htmlspecialchars($row['fullname']); ?></td>
                                  <td><?= htmlspecialchars($row['email']); ?></td>
                                  <td><?= htmlspecialchars($row['mobile']); ?></td>
                                  <td><?= number_format($row['total_amount'], 2); ?></td>
                                  <td><?= ucfirst($row['status']); ?></td>
                                  <td><?= date('d-m-Y', strtotime($row['order_date'])); ?></td>
                                  <td>
                                      <a href="invoice.php?id=<?= $row['order_id']; ?>" 
                                         class="btn btn-sm btn-danger">
                                         View Invoice
                                      </a>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr><td colspan="8" class="text-center">No orders found</td></tr>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>
      </div>
  </div>

<?php include 'admin-footer.php'; ?>

<!-- Chart Script -->
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
const chartLabels = <?= json_encode($labels) ?>;
const chartValues = <?= json_encode($values) ?>;

if (chartLabels.length > 0) {
    // Gradient for area fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(220, 20, 60, 0.6)'); 
    gradient.addColorStop(1, 'rgba(220, 20, 60, 0.05)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Total Amount (₹)',
                data: chartValues,
                fill: true,
                borderColor: 'crimson',
                backgroundColor: gradient,
                tension: 0.3,
                pointBackgroundColor: 'crimson',
                pointBorderColor: 'maroon',
                pointHoverBackgroundColor: 'maroon',
                pointHoverBorderColor: '#fff',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                x: {
                    ticks: { color: 'maroon', maxRotation: 90, minRotation: 45 },
                    grid: { color: 'rgba(128,0,0,0.1)' }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: 'maroon' },
                    grid: { color: 'rgba(128,0,0,0.1)' }
                }
            }
        }
    });
} else {
    ctx.font = "16px Arial";
    ctx.fillText("No data available for the selected range", 10, 50);
}
</script>
</body>
</html>
