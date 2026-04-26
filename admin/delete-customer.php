<?php
require_once __DIR__ . '/con.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Get customer email
    $stmt = $con->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $email = $result->fetch_assoc()['email'] ?? null;

    if ($email) {
        // Delete orders linked with email
        $stmtOrders = $con->prepare("DELETE FROM orders WHERE email = ?");
        $stmtOrders->bind_param('s', $email);
        $stmtOrders->execute();
    }

    // Delete user
    $stmtDelete = $con->prepare("DELETE FROM users WHERE id = ?");
    $stmtDelete->bind_param('i', $id);
    $stmtDelete->execute();

    header("Location: customers.php?msg=Customer+deleted+successfully");
    exit;
} else {
    header("Location: customers.php?msg=Invalid+request");
    exit;
}
?>
