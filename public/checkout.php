<?php
require_once 'classes/Database.php';
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
$db = Database::getInstance()->getConnection();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = $db->real_escape_string($_POST['full_name']);
    $email = $db->real_escape_string($_POST['email']);
    $address = $db->real_escape_string($_POST['address']);
    $phone = $db->real_escape_string($_POST['phone']);
    $user_id = $sessionManager->getUserId();

    // Get cart items
    $cart_query = "SELECT sc.product_id, sc.quantity, p.price 
                   FROM shopping_cart sc
                   JOIN products p ON sc.product_id = p.id
                   WHERE sc.user_id = $user_id";
                   
    $cart_result = $db->query($cart_query);
    
    if (!$cart_result) {
        $error = "Error retrieving cart items: " . $db->error;
    } elseif ($cart_result->num_rows === 0) {
        $error = "Your cart is empty";
    } else {
        // Calculate total
        $total = 0;
        $cart_items = [];
        while ($row = $cart_result->fetch_assoc()) {
            $total += $row['price'] * $row['quantity'];
            $cart_items[] = $row;
        }

        // Create order
        $order_query = "INSERT INTO orders (user_id, total_price, status) 
                        VALUES ($user_id, $total, 'Paid')";
                        
        if ($db->query($order_query)) {
            $order_id = $db->insert_id;

            // Insert order items
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                
                $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                                     VALUES ($order_id, $product_id, $quantity, $price)";
                $db->query($order_item_query);
            }

            // Clear cart
            $clear_cart_query = "DELETE FROM shopping_cart WHERE user_id = $user_id";
            $db->query($clear_cart_query);

            // Redirect to confirmation
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
    <!-- Existing head content remains the same -->
</head>
<body>
    <!-- Existing header content remains the same -->

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="bg-gray-100 p-6 rounded-lg shadow-lg max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold mb-4">Billing Information</h2>
                <form action="checkout.php" method="POST">
                    <!-- Updated form fields with correct names -->
                    <div class="mb-4">
                        <label for="full-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input class="form-input" placeholder="Full name" type="text" name="full_name" required/>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input class="form-input" placeholder="kavindu@gmail.com" type="email" name="email" required/>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input class="form-input" placeholder="521 road city" type="text" name="address" required/>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input class="form-input" placeholder="+94712603723" type="tel" name="phone" required/>
                    </div>

                    <!-- Payment Section (simulated) -->
                    <h2 class="text-2xl font-bold mt-8 mb-4">Payment Information</h2>
                    <div class="mb-4">
                        <label for="card-number" class="block text-sm font-medium text-gray-700">Card Number</label>
                        <input class="form-input" placeholder="xxxx xxxx xxxx xxxx" type="text" pattern="\d{16}" required/>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="expiry-date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input class="form-input" placeholder="MM/YY" type="text" pattern="(0[1-9]|1[0-2])\/\d{2}" required/>
                        </div>
                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                            <input class="form-input" placeholder="123" type="text" pattern="\d{3}" required/>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            Complete Purchase
                        </button>
                    </div>
                </form>
            </div>
            <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

        </div>
    </section>
</body>
</html>