<?php
session_start();
include '../php/config.php';
$pageTitle = "Manage Food - Admin";
include 'admin-header.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = $conn->query("SELECT image_path FROM food_items WHERE id=$id");
    $row = $result->fetch_assoc();

    if ($row && file_exists("../" . $row['image_path'])) {
        unlink("../" . $row['image_path']); // Delete image file
    }
    $conn->query("DELETE FROM food_items WHERE id=$id");
    header("Location: manage-food.php?msg=deleted");
    exit;
}

// Fetch category filter
$categoryFilter = $_GET['category'] ?? '';
$query = "SELECT * FROM food_items";
if (!empty($categoryFilter)) {
    $query .= " WHERE category='" . $conn->real_escape_string($categoryFilter) . "'";
}
$result = $conn->query($query);
?>

<style>
    /* Table Container */
    .table-wrapper {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    .table img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
    }
    .table thead {
        background: #343a40;
        color: white;
    }

    /* Button Colors */
    :root {
        --red: #ff4d4d;
        --red-hover: #e63e3e;
        --orange: #ff9800;
        --orange-hover: #e68900;
    }

    /* Action Buttons */
    .action-btn {
        padding: 6px 14px;
        font-size: 0.9rem;
        border-radius: 20px;
        border: none;
        color: #fff;
        cursor: pointer;
        margin: 2px;
        display: inline-block;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    /* Edit Button */
    .edit-btn {
        background-color: var(--orange);
    }
    .edit-btn:hover {
        background-color: var(--orange-hover);
        box-shadow: 0 4px 6px rgba(255,152,0,0.3);
        transform: translateY(-2px);
    }

    /* Delete Button */
    .delete-btn {
        background-color: var(--red);
    }
    .delete-btn:hover {
        background-color: var(--red-hover);
        box-shadow: 0 4px 6px rgba(255,77,77,0.3);
        transform: translateY(-2px);
    }
</style>

<div class="container mt-4">
    <h2 class="fw-bold text-dark mb-3">Manage Food Items 🍽️</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">✅ Item deleted successfully!</div>
    <?php endif; ?>

    <!-- Filter Form -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="gallery" <?= ($categoryFilter=='gallery')?'selected':'' ?>>Gallery</option>
                <option value="popular" <?= ($categoryFilter=='popular')?'selected':'' ?>>Popular</option>
                <option value="speciality" <?= ($categoryFilter=='speciality')?'selected':'' ?>>Speciality</option>
            </select>
        </div>
        <div class="col-md-2">
            <a href="manage-food.php" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price (₹)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><img src="../<?= $row['image_path'] ?>" alt=""></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= ucfirst($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td>
                            <a href="edit-food.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">Edit</a>
                            <a href="manage-food.php?delete=<?= $row['id'] ?>" 
                               onclick="return confirm('Delete this item?')" 
                               class="action-btn delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No food items found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin-footer.php'; ?>
