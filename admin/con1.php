<?php
$con = new mysqli("localhost", "root", "", "online_food_delivery");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>