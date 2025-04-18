<?php
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/ShoppingCart.php';
require_once '../includes/session_manager.php';


$sessionManager = new SessionManager();
$userId = $sessionManager->getUserId();
$cart = new ShoppingCart();
$cartItems = $userId ? $cart->getCartItems($userId) : [];

// Calculate totals
$subtotal = 0;
$tax = 0;
$discount = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.02; // 2% tax
$total = $subtotal + $tax - $discount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        nav, .content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8">Shopping Cart</p>
        <div class="pb-8">
            <a href="Home_Page.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Return To Home 
            </a>
        </div>
    </div>

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6">
                        <?php if (empty($cartItems)): ?>
                            <div class="rounded-lg border border-gray-200 bg-gray-200 p-4 shadow-sm md:p-6">
                                <p class="text-center text-gray-700">Your cart is empty</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($cartItems as $item): ?>
                                <div class="rounded-lg border border-gray-200 bg-gray-200 p-4 shadow-sm md:p-6">
                                    <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                        <a href="#" class="shrink-0 md:order-1">
                                            <img class="hidden h-20 w-20 dark:block" src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                                        </a>

                                        <div class="flex items-center justify-between md:order-3 md:justify-end">
                                            <!-- quantity control-->
                                                <div class="flex items-center">
                                                    <form action="update_cart.php" method="POST" class="flex items-center">
                                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                                                        <button type="submit" name="action" value="decrease" class="inline-flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-gray-100">
                                                            <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                            </svg>
                                                        </button>
                                                        <input type="text" readonly value="<?php echo $item['quantity']; ?>" class="w-10 border-0 bg-transparent text-center text-sm font-medium text-black" />
                                                        <button type="submit" name="action" value="increase" class="inline-flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-gray-100">
                                                            <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                
                                            <div class="text-end md:order-4 md:w-32">
                                                <p class="text-base font-bold text-black">LKR <?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                            </div>
                                        </div>

                                        <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                            <a href="#" class="text-base font-medium hover:underline text-black"><?php echo htmlspecialchars($item['name']); ?></a>
                                            <form action="update_cart.php" method="POST" class="inline">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <button type="submit" name="action" value="remove" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline">
                                                    <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mx-auto mt-16 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                    <div class="space-y-4 rounded-lg mt-8 border p-4 shadow-sm bg-gray-200 sm:p-6">
                        <p class="text-xl font-semibold text-black">Order summary</p>
                        <div class="space-y-4">
                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-black">Original price</dt>
                                <dd class="text-base font-medium text-black">LKR <?php echo number_format($subtotal, 2); ?></dd>
                            </dl>
                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-black">Discount</dt>
                                <dd class="text-base font-medium text-black">-LKR <?php echo number_format($discount, 2); ?></dd>
                            </dl>
                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-black">Tax</dt>
                                <dd class="text-base font-medium text-black">LKR <?php echo number_format($tax, 2); ?></dd>
                            </dl>
                            <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2">
                                <dt class="text-base font-bold text-black">Total</dt>
                                <dd class="text-base font-bold text-black">LKR <?php echo number_format($total, 2); ?></dd>
                            </dl>
                        </div>

                        <a href="checkout.php" class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-black hover:bg-primary-800">Proceed to Checkout</a>
                        <?php if (isset($_SESSION['error'])): ?>
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-screen-xl mx-auto mt-4" role="alert">
                                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                                    <?php unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-sm font-normal text-black">or</span>
                            <a href="product.php" class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline">
                                Continue Shopping
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.html'; ?>
</body>
</html>