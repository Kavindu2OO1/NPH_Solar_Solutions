<?php 
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/ShoppingCart.php';
require_once '../includes/session_manager.php';
include 'navbar.php';

//session_start();
//$userId = $_SESSION['user_id'] ?? null; // Assuming you have user authentication
$sessionManager = new SessionManager();
$userId = $sessionManager->getUserId();
$productObj = new Product();
$products = $productObj->getAllProducts();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>projects</title>
    <style>
    body {
        background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
    }

    nav,
    .content {
        position: relative;
        z-index: 10;
    }
    /* Background Styling */
    body {
            background-image: url('/NPH_Solar_Solutions/pictures/solar-panels-roof-solar-cell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        

        /* Ensure content is visible over the background */
        nav, .content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="items1 bg-gray-100 mt-11 p-4 size-full grid grid-cols-4 gap-4">
        <?php foreach ($products as $product): ?>
            <div class="max-w-sm ml-11 bg-white border border-gray-200 rounded-lg shadow">
                <a href="#">
                    <img class="rounded-t-lg scale-75" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                </a>
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900"><?php echo htmlspecialchars($product['name']); ?></h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700"><?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xl font-bold text-gray-900">LKR <?php echo number_format($product['price'], 2); ?></span>
                    </div>

                    <form action="add_to_cart.php" method="POST" class="inline-flex items-center">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                            Add to Cart
                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

   
</body>
 <?php include 'footer.html'; ?>
</html>