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

    public function getProductsWithPagination($search = '', $limit = 8, $offset = 0) {
        $products = [];
        
        if (empty($search)) {
            $sql = "SELECT * FROM products LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
        } else {
            $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ? LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }

    public function getTotalProducts($search = '') {
        if (empty($search)) {
            $sql = "SELECT COUNT(*) as total FROM products";
            $result = $this->db->query($sql);
        } else {
            $sql = "SELECT COUNT(*) as total FROM products WHERE name LIKE ? OR description LIKE ?";
            $stmt = $this->db->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
        }
        
        return $result->fetch_assoc()['total'];
    }
}