<?php
require_once 'classes/Database.php';
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
$db = Database::getInstance()->getConnection();

$error = null;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $db->real_escape_string($_POST['full_name']);
    $email = $db->real_escape_string($_POST['email']);
    $address = $db->real_escape_string($_POST['address']);
    $phone = $db->real_escape_string($_POST['phone']);
    $user_id = $sessionManager->getUserId();

    $customer_info = [
        "shipping_first_name" => $full_name, // You might want to split full_name into first and last
        "shipping_address" => $address,
        "shipping_email" => $email,
        "shipping_phone" => $phone
    ];

        // Convert the array to a JSON string and escape it for SQL
    $customer_info_json = $db->real_escape_string(json_encode($customer_info));

    $cart_query = "SELECT sc.product_id, sc.quantity, p.price FROM shopping_cart sc JOIN products p ON sc.product_id = p.id WHERE sc.user_id = $user_id";
    
    $cart_result = $db->query($cart_query);
    
    if (!$cart_result) {
        $error = "Error retrieving cart items: " . $db->error;
    } elseif ($cart_result->num_rows === 0) {
        $error = "Your cart is empty";
    } else {
        $total = 0;
        $cart_items = [];
        while ($row = $cart_result->fetch_assoc()) {
            $total += $row['price'] * $row['quantity'];
            $cart_items[] = $row;
        }

        $order_query = "INSERT INTO orders (user_id, total_price, status, customer_info) 
                VALUES ($user_id, $total, 'Paid', '$customer_info_json')";
        
        if ($db->query($order_query)) {
            $order_id = $db->insert_id;
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                
                $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)";
                $db->query($order_item_query);
            }

                        // ★ Update product stock ★
                    $update_stock_query = "UPDATE products 
                    SET stock = stock - $quantity 
                    WHERE id = $product_id";
            if (!$db->query($update_stock_query)) {
            $error = "Error updating product stock: " . $db->error;
              // Exit the loop if there's an error
            }

            $clear_cart_query = "DELETE FROM shopping_cart WHERE user_id = $user_id";
            $db->query($clear_cart_query);

            header("Location: confirmation.php?order_id=$order_id");
            exit();
        } else {
            $error = "Error creating order: " . $db->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900">
    <section class="py-12 px-4 md:py-20">
        <div class="max-w-3xl mx-auto bg-white p-8 shadow-lg rounded-lg border border-gray-200">
            <h2 class="text-3xl font-bold text-orange-600 mb-6">Shipping Information</h2>
            <form action="checkout.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="text" name="full_name" required/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="email" name="email" required/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="text" name="address" required/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="tel" name="phone" required/>
                </div>
                
                <h2 class="text-3xl font-bold text-orange-600 mt-8">Payment Information</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Card Number</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="text" pattern="\d{16}" required/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                        <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="text" pattern="(0[1-9]|1[0-2])\/\d{2}" required/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CVC</label>
                        <input class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400" type="text" pattern="\d{3}" required/>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg shadow-md mt-4">
                    Complete Purchase
                </button>
            </form>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4 text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
