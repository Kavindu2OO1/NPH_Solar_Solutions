<?php
    // Shopping Cart class
    class ShoppingCart {
        private $db;
        
        public function __construct() {
            $this->db = Database::getInstance()->getConnection();
        }
        
        public function addToCart($userId, $productId, $quantity = 1) {
            // Check if product already exists in cart
            $sql = "SELECT * FROM shopping_cart WHERE user_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Update quantity
                $sql = "UPDATE shopping_cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iii", $quantity, $userId, $productId);
            } else {
                // Insert new item
                $sql = "INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iii", $userId, $productId, $quantity);
            }
            return $stmt->execute();
        }
        
        public function getCartItems($userId) {
            $sql = "SELECT sc.*, p.name, p.price, p.image_url 
                    FROM shopping_cart sc 
                    JOIN products p ON sc.product_id = p.id 
                    WHERE sc.user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $items = [];
            
            while($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return $items;
        }
        
        public function updateQuantity($userId, $productId, $quantity) {
            $sql = "UPDATE shopping_cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iii", $quantity, $userId, $productId);
            return $stmt->execute();
        }
        
        public function removeItem($userId, $productId) {
            $sql = "DELETE FROM shopping_cart WHERE user_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            return $stmt->execute();
        }
    }
?>