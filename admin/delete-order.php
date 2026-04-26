<?php
include '../php/config.php'; // DB connection

if (isset($_GET['id'])) {
    $orderId = intval($_GET['id']); // sanitize input

    // Delete order
    $sql = "DELETE FROM orders WHERE order_id = $orderId";

    if ($conn->query($sql) === TRUE) {
        header("Location: orders-history.php?msg=Order deleted successfully");
        exit();
    } else {
        header("Location: orders-history.php?msg=Failed to delete order");
        exit();
    }
} else {
    header("Location: orders-history.php?msg=Invalid request");
    exit();
}
?>
