<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../models/order.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id not set");
    echo json_encode(['error' => 'Vui lòng đăng nhập để xem đơn hàng']);
    exit;
}
$userId = $_SESSION['user_id'];
error_log("Fetching orders for user_id: $userId");

$order = new Order();
$orders = $order->getOrdersByUser($userId);
echo json_encode($orders);
?>