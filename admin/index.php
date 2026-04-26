<?php
$pageTitle = "Dashboard - Admin";
include 'admin-header.php';
include '../php/config.php';

// Fetch counts
$totalFoods = $conn->query("SELECT COUNT(*) AS total FROM food_items")->fetch_assoc()['total'];
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$completedOrders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status='completed'")->fetch_assoc()['total'];
$pendingOrders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status='pending'")->fetch_assoc()['total'];
$cancelledOrders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status='cancelled'")->fetch_assoc()['total'];
$totalCustomers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalDeliveryBoys = $conn->query("SELECT COUNT(*) AS total FROM delivery_boys")->fetch_assoc()['total'];

// Recent Orders filter from query param
$recentFilter = isset($_GET['recent']) ? $_GET['recent'] : '';
$allowedStatuses = ['pending', 'completed', 'cancelled'];
if ($recentFilter && in_array($recentFilter, $allowedStatuses)) {
    $recentQuery = "SELECT * FROM orders WHERE status='$recentFilter' ORDER BY order_id DESC LIMIT 5";
} else {
    $recentQuery = "SELECT * FROM orders ORDER BY order_id DESC LIMIT 5";
}
$recentResult = $conn->query($recentQuery);
?>

<style>
    /* Grid for Stats Cards */
.dashboard-grid {
    display: grid;
    grid-auto-flow: column; /* horizontal flow */
    grid-auto-columns: 140px; /* smaller width for each box */
    gap: 0.8rem;
    overflow-x: auto;
    padding-bottom: 10px;
    white-space: nowrap;
}



    /* Stats Cards */
   .stats-card {
    border-radius: 12px;
    color: white;
    padding: 15px;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 90px; /* smaller height */
}

    .stats-card:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

.stats-card h5 {
    margin: 0;
    font-size: 0.9rem;
}

.stats-card h3 {
    margin: 5px 0 0;
    font-size: 1.4rem;
    font-weight: bold;
}

    /* Background gradients */
    .bg-foods { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .bg-orders { background: linear-gradient(135deg, #43e97b, #38f9d7); }
    .bg-completed { background: linear-gradient(135deg, #ff9a9e, #fad0c4); }
    .bg-pending { background: linear-gradient(135deg, #f6d365, #fda085); }
    .bg-cancelled { background: linear-gradient(135deg, #d53369, #daae51); }
    .bg-delivery { background: linear-gradient(135deg, #7F00FF, #E100FF); }

    /* Quick Actions Grid */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Quick Action Cards */
    .quick-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .quick-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .quick-card i {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    /* Recent orders filters */
    .recent-filter a {
        margin-right: 10px;
        cursor: pointer;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead {
        background: #f8f9fa;
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    /* Badge style in table */
    .badge {
        padding: 0.35em 0.7em;
        font-size: 0.85rem;
        border-radius: 0.35rem;
        text-decoration: none;
        color: white;
        display: inline-block;
    }

    .badge-warning { background-color: #f6d365; color: #7a5901; }
    .badge-success { background-color: #43e97b; }
    .badge-danger { background-color: #d53369; }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-card h3 { font-size: 1.4rem; }
        .quick-card i { font-size: 2rem; }
    }
</style>

<div class="container mt-4">
    <h2 class="fw-bold text-dark mb-2">Welcome, Admin 👋</h2>
    <p class="text-muted mb-4">Here's an overview of your system.</p>

    <!-- Stats Cards -->
    <div class="dashboard-grid">
        <a href="manage-food.php" class="stats-card bg-foods" title="Total Foods">
            <h5>Foods</h5>
            <h3><?= $totalFoods ?></h3>
        </a>
        <a href="orders-history.php" class="stats-card bg-orders" title="Total Orders">
            <h5>Orders</h5>
            <h3><?= $totalOrders ?></h3>
        </a>
        <a href="orders-history.php?status=completed" class="stats-card bg-completed" title="Completed Orders">
            <h5>Completed</h5>
            <h3><?= $completedOrders ?></h3>
        </a>
        <a href="orders-history.php?status=pending" class="stats-card bg-pending" title="Pending Orders">
            <h5>Pending</h5>
            <h3><?= $pendingOrders ?></h3>
        </a>
        <a href="orders-history.php?status=cancelled" class="stats-card bg-cancelled" title="Cancelled Orders">
            <h5>Cancelled</h5>
            <h3><?= $cancelledOrders ?></h3>
        </a>
        <a href="customers.php" class="stats-card bg-orders" title="Total Customers">
            <h5>Customers</h5>
            <h3><?= $totalCustomers ?></h3>
        </a>
        <a href="manage-delivery-boys.php" class="stats-card bg-delivery" title="Delivery Boys">
            <h5>Delivery Boys</h5>
            <h3><?= $totalDeliveryBoys ?></h3>
        </a>
    </div>

    

    <!-- Recent Orders Section -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white fw-bold fs-5">
            Recent Orders
        </div>
        <div class="card-body p-3">
            <!-- Filter Badges -->
            <div class="recent-filter mb-3">
                <a href="?recent=pending" class="badge badge-warning text-decoration-none">Pending</a>
                <a href="?recent=completed" class="badge badge-success text-decoration-none">Completed</a>
                <a href="?recent=cancelled" class="badge badge-danger text-decoration-none">Cancelled</a>
                <a href="index.php" class="badge bg-secondary text-white text-decoration-none">All</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recentResult->num_rows > 0): ?>
                            <?php while ($row = $recentResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                                    <td>
                                        <?php
                                            $status = strtolower($row['status']);
                                            $badgeClass = '';
                                            $statusLabel = ucfirst($status);
                                            if ($status === 'pending') {
                                                $badgeClass = 'badge-warning';
                                            } elseif ($status === 'completed') {
                                                $badgeClass = 'badge-success';
                                            } else {
                                                $badgeClass = 'badge-danger';
                                            }
                                        ?>
                                        <a href="?recent=<?= $status ?>" class="badge <?= $badgeClass ?> text-decoration-none"><?= $statusLabel ?></a>
                                    </td>
                                    <td><?= date("d M Y", strtotime($row['order_date'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No recent orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'admin-footer.php'; ?>
