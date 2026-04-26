<?php
include '../php/config.php'; // DB connection

header('Content-Type: application/json');

// Request data lena
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order_id'], $data['status'])) {
    $order_id = intval($data['order_id']);
    $status = $data['status'];

    // Valid status check
    $allowed = ['pending', 'completed', 'cancelled'];
    if (!in_array($status, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }

    // DB update query
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
$conn->close();
?>
