<?php
require_once 'classes/ShoppingCart.php';
require_once 'classes/Database.php';  // Add this line
require_once 'classes/Product.php';   // Add this line
require_once '../includes/session_manager.php';
$sessionManager = new SessionManager();
$userId = $sessionManager->getUserId();
//session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        header('Location: signin.php');
        exit;
    }

    $productId = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    if ($productId) {
        $cart = new ShoppingCart();
        $cart->addToCart($userId, $productId, $quantity);
        header('Location: product.php');
        exit;
    }
}

header('Location: product.php');
exit;