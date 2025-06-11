<?php
header("Content-Type: application/json");
require_once "../../config/database.php";
require_once "../../models/cart.php";

session_start();
$cart = new Cart();

if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id not set");
    echo json_encode(['error' => 'Vui lòng đăng nhập để sử dụng giỏ hàng']);
    exit;
}
$userId = $_SESSION['user_id'];
error_log("Adding to cart for user_id: $userId");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cartItems = $cart->getCartItems($userId);
    error_log("Cart items returned: " . print_r($cartItems, true));
    echo json_encode($cartItems);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['productId']) && isset($data['quantity'])) {
        $result = $cart->addToCart($userId, $data['productId'], $data['quantity']);
        echo json_encode(['success' => $result]);
    } else {
        echo json_encode(['success' => false]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['cartId'])) {
        $result = $cart->removeItem($data['cartId'], $userId);
        echo json_encode(['success' => $result]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['cartId']) && isset($data['quantity'])) {
        $result = $cart->updateQuantity($data['cartId'], $userId, $data['quantity']);
        echo json_encode(['success' => $result]);
    }
}
?>