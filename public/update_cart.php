<?php
require_once 'classes/Database.php';
require_once 'classes/ShoppingCart.php';
require_once 'classes/Product.php';
require_once '../includes/session_manager.php';
$sessionManager = new SessionManager();
$userId = $sessionManager->getUserId();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: signin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = new ShoppingCart();
    $productId = $_POST['product_id'] ?? null;
    $action = $_POST['action'] ?? null;
    
    if ($productId && $action) {
        // Get current product stock
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        // Get current cart quantity
        $cartItems = $cart->getCartItems($userId);
        $currentQty = 0;
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $productId) {
                $currentQty = $item['quantity'];
                break;
            }
        }

        switch ($action) {
            case 'increase':
                // Check if increasing quantity would exceed available stock
                if ($currentQty < $product['stock']) {
                    $cart->updateQuantity($userId, $productId, $currentQty + 1);
                } else {
                    $_SESSION['error'] = "Cannot add more items. Only " . $product['stock'] . " units available in stock.";
                }
                break;
                
            case 'decrease':
                if ($currentQty > 1) {
                    $cart->updateQuantity($userId, $productId, $currentQty - 1);
                }
                break;
                
            case 'remove':
                $cart->removeItem($userId, $productId);
                break;
        }
    }
}

header('Location: shoppingcart.php');
exit;