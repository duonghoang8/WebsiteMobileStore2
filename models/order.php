<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getOrdersByUser($userId) {
        try {
            $sql = "SELECT o.order_id, o.order_date, o.total_price, o.status
                    FROM orders o
                    WHERE o.user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched orders for user $userId: " . print_r($results, true)); // Debug
            return $results;
        } catch (PDOException $e) {
            error_log("Error in getOrdersByUser: " . $e->getMessage());
            return [];
        }
    }
}
?>