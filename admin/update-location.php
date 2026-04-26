<?php
include '../php/config.php';

$order_id = intval($_GET['order_id']);
$delivery_boy_id = intval($_GET['delivery_boy_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lat = floatval($_POST['lat']);
    $lng = floatval($_POST['lng']);

    // अगर पहले से entry है तो update करो
    $check = $conn->query("SELECT id FROM delivery_tracking 
                           WHERE order_id=$order_id AND delivery_boy_id=$delivery_boy_id");

    if ($check->num_rows > 0) {
        $conn->query("UPDATE delivery_tracking 
                      SET lat='$lat', lng='$lng', updated_at=NOW() 
                      WHERE order_id=$order_id AND delivery_boy_id=$delivery_boy_id");
    } else {
        $conn->query("INSERT INTO delivery_tracking(order_id, delivery_boy_id, lat, lng) 
                      VALUES($order_id, $delivery_boy_id, '$lat', '$lng')");
    }

    echo "Location updated successfully";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Location</title>
</head>
<body>
<h2>Update Location (Delivery Boy)</h2>
<form method="POST">
    Latitude: <input type="text" name="lat" required><br>
    Longitude: <input type="text" name="lng" required><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
