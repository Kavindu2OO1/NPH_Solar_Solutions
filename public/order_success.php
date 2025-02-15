
<?php
// order_success.php
require_once '../includes/session_manager.php';
require_once 'classes/Order.php';

$sessionManager = new SessionManager();
$userId = $sessionManager->getUserId();

if (!$userId || !isset($_SESSION['last_order_id'])) {
    header('Location: Home_Page.php');
    exit();
}

$orderId = $_SESSION['last_order_id'];
$order = new Order();
$orderDetails = $order->getOrder($orderId);
$orderItems = $order->getOrderItems($orderId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - NPH Solar Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Order Confirmed!</h2>
                <p class="mt-2 text-gray-600">Thank you for your purchase. Your order has been received.</p>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Order ID: #<?php echo $orderId; ?></p>
                    <p class="text-sm text-gray-600">Total: LKR <?php echo number_format($orderDetails['total_price'], 2); ?></p>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="Home_Page.php" class="block w-full text-center bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>