<?php
include '../php/config.php';

$response = ['success'=>false];

if(isset($_POST['order_id'], $_POST['status'])){
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    if(in_array($status,['pending','confirmed','cancelled'])){
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
        $stmt->bind_param("si",$status,$order_id);
        if($stmt->execute()){
            $response['success'] = true;
        }
        $stmt->close();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
