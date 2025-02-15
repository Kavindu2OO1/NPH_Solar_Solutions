<?php
require_once '../includes/session_manager.php';
require_once 'classes/Database.php';

$sessionManager = new SessionManager();
$db = Database::getInstance()->getConnection();

// Get order ID from URL
$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
$userId = $sessionManager->getUserId();

// Initialize variables
$order = [];
$items = [];
$error = null;

if ($orderId) {
    try {
        // Get order details
        $stmt = $db->prepare("SELECT o.*, u.first_name, u.last_name
                            FROM orders o
                            JOIN users u ON o.user_id = u.id
                            WHERE o.id = ? AND o.user_id = ?");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $db->error);
        }

        $stmt->bind_param("ii", $orderId, $userId);
        $stmt->execute();
        $orderResult = $stmt->get_result();
        $order = $orderResult->fetch_assoc();
        $stmt->close();

        if (!$order) {
            throw new Exception("Order not found");
        }

        // Get order items
        $stmt = $db->prepare("SELECT oi.*, p.name 
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = ?");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $db->error);
        }

        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $itemsResult = $stmt->get_result();
        $items = $itemsResult->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <?php if ($order): ?>
                <h1 class="text-3xl font-bold mb-4">Order Confirmation #<?= htmlspecialchars($order['id']) ?></h1>
                <div class="mb-6">
                    <p class="text-lg"><span class="font-semibold">Status:</span> <?= htmlspecialchars($order['status']) ?></p>
                    <p class="text-lg"><span class="font-semibold">Total:</span> LKR <?= number_format($order['total_price'], 2) ?></p>
                    <p class="text-lg"><span class="font-semibold">Order Date:</span> <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                </div>

                <h2 class="text-2xl font-bold mb-4">Items:</h2>
                <ul class="space-y-2">
                    <?php foreach ($items as $item): ?>
                        <li class="border-b pb-2">
                            <p class="font-semibold"><?= htmlspecialchars($item['name']) ?></p>
                            <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                            <p>Price: LKR <?= number_format($item['price'], 2) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="mt-8">
                    <a href="Home_Page.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Return to Home
                    </a>
                </div>
            <?php else: ?>
                <div class="text-red-500 text-xl text-center">
                    Order not found or you don't have permission to view this order.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>