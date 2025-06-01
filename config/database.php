<?php
class Database {
    private $host = "localhost";
    private $db_name = "mobie_store_web";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            return null; // Trả về null để caller xử lý
        }
        return $this->conn;
    }
}
?>