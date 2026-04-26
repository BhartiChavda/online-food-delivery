<?php
// admin/includes/db.php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
date_default_timezone_set('Asia/Kolkata');

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';          // XAMPP/MAMP મુજબ બદલો
$DB_NAME = 'online_food_delivery';

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (Exception $e) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}
