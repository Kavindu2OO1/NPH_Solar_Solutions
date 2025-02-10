<?php
    class Product {
        private $db;
        
        public function __construct() {
            $this->db = Database::getInstance()->getConnection();
        }
        
        public function getAllProducts() {
            $sql = "SELECT * FROM products";
            $result = $this->db->query($sql);
            $products = [];
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
            }
            return $products;
        }
        
        public function getProduct($id) {
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
    }
?>