<?php
require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;

    public function __construct() {
        $db = new Database();
       $this->conn = $db->getConnection();
    }

    public function addToCart($userId, $productId, $quantity) {
        $sql = "INSERT INTO cart (user_id, product_id, quantity)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $productId, $quantity, $quantity]);
    }
}
?>