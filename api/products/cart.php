<?php
require_once __DIR__ . '/../../models/cart.php';

// Nhận method từ client (POST, GET, DELETE)
$method = $_SERVER['REQUEST_METHOD'];
$cart = new Cart();

header('Content-Type: application/json');

// Giả sử user đã đăng nhập và có user_id
session_start();
$userId = $_SESSION['user_id'] ?? 1; 

switch ($method) {
    case 'POST':
        // Thêm sản phẩm vào giỏ hàng
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'];
        $quantity = $data['quantity'] ?? 1;

        if ($cart->addToCart($userId, $productId, $quantity)) {
            echo json_encode(['message' => 'Đã thêm vào giỏ hàng']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Lỗi khi thêm vào giỏ hàng']);
        }
        break;

    case 'GET':
        // Lấy giỏ hàng của người dùng
        $items = $cart->getCartItems($userId);
        echo json_encode($items);
        break;

    case 'DELETE':
        // Xóa 1 sản phẩm khỏi giỏ hàng
        parse_str(file_get_contents("php://input"), $data);
        $productId = $data['product_id'];

        if ($cart->removeItem($userId, $productId)) {
            echo json_encode(['message' => 'Đã xóa sản phẩm khỏi giỏ']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Lỗi khi xóa sản phẩm']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method không được hỗ trợ']);
        break;
}
