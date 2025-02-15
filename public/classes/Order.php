
<?php
// classes/Order.php
class Order {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function createOrder($userId, $totalPrice, $customerInfo) {
        $sql = "INSERT INTO orders (user_id, total_price, status, customer_info) 
                VALUES (?, ?, 'Pending', ?)";
        $params = [$userId, $totalPrice, json_encode($customerInfo)];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function addOrderItem($orderId, $productId, $quantity, $price) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)";
        $params = [$orderId, $productId, $quantity, $price];
        
        $this->db->query($sql, $params);
    }
    
    public function updateProductStock($productId, $quantity) {
        $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $params = [$quantity, $productId];
        
        $this->db->query($sql, $params);
    }
    
    public function getOrder($orderId) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->query($sql, [$orderId])->fetch();
    }
    
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name, p.image_url 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = ?";
        return $this->db->query($sql, [$orderId])->fetchAll();
    }
}
?>