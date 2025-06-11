<?php
require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

   public function addToCart($userId, $productId, $quantity) {
    try {
        $checkSql = "SELECT stock_quantity FROM products WHERE product_id = ? AND status = 'active'";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([$productId]);
        $product = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $product['stock_quantity'] >= $quantity) {
            $sql = "INSERT INTO cart (user_id, product_id, quantity)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE quantity = GREATEST(quantity + ?, 1)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId, $productId, $quantity, $quantity]);
        }
        return false;
    } catch (PDOException $e) {
        error_log("Error in addToCart: " . $e->getMessage());
        return false;
    }
}
    public function getCartItems($userId) {
        try {
            $sql = "SELECT c.cart_id, p.product_id, p.name, p.price, p.image_url, c.quantity
                    FROM cart c
                    JOIN products p ON c.product_id = p.product_id
                    WHERE c.user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCartItems: " . $e->getMessage());
            return [];
        }
    }

    public function removeItem($cartId, $userId) {
        try {
            $sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$cartId, $userId]);
        } catch (PDOException $e) {
            error_log("Error in removeItem: " . $e->getMessage());
            return false;
        }
    }

    public function clearCart($userId) {
        try {
            $sql = "DELETE FROM cart WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error in clearCart: " . $e->getMessage());
            return false;
        }
    }

    public function updateQuantity($cartId, $userId, $quantity) {
        try {
            $checkSql = "SELECT p.stock_quantity, c.quantity AS current_quantity
                        FROM cart c
                        JOIN products p ON c.product_id = p.product_id
                        WHERE c.cart_id = ? AND c.user_id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$cartId, $userId]);
            $item = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($item && ($item['stock_quantity'] >= $quantity)) {
                $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$quantity, $cartId, $userId]);
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in updateQuantity: " . $e->getMessage());
            return false;
        }
    }
}

?>