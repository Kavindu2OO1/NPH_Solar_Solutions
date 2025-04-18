<?php 
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/ShoppingCart.php';
require_once '../includes/session_manager.php';
include 'navbar.php';

$sessionManager = new SessionManager();
$userId = $sessionManager->getUserId();
$productObj = new Product();

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination settings
$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Get products with pagination and search
$products = $productObj->getProductsWithPagination($search, $itemsPerPage, $offset);
$totalProducts = $productObj->getTotalProducts($search);
$totalPages = ceil($totalProducts / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        body {
            background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        nav,
        .content {
            position: relative;
            z-index: 10;
        }

        /* Container max-width control for different screen sizes */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Search form responsive styles */
        .search-container {
            width: 100%;
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
        }

        /* Product grid responsive styles */
        .products-grid {
            display: grid;
            gap: 1rem;
            padding: 1rem;
            width: 100%;
            background-color: rgba(243, 244, 246, 0.95);
            margin-top: 2rem;
        }

        /* Mobile first - single column */
        @media (max-width: 639px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .product-card {
                margin: 0 auto;
                width: 100%;
                max-width: 320px;
            }
        }

        /* Tablet - two columns */
        @media (min-width: 640px) and (max-width: 1023px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Desktop - four columns */
        @media (min-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Product card responsive styles */
        .product-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-content {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Pagination responsive styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 1rem;
            margin: 2rem 0;
        }

        .pagination-button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        @media (max-width: 639px) {
            .pagination {
                gap: 0.25rem;
            }

            .pagination-button {
                padding: 0.25rem 0.75rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Search Form -->
        <div class="search-container">
            <form action="" method="GET" class="search-form">
                <input type="text" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search products..." 
                       class="search-input p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    Search
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php if (empty($products)): ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600 text-lg">No products found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <a href="#" class="product-image-container">
                            <img class="product-image" 
                                 src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        </a>
                        <div class="product-content">
                            <a href="#">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900"><?php echo htmlspecialchars($product['name']); ?></h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700"><?php echo htmlspecialchars($product['description']); ?></p>
                            
                            <div class="mt-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xl font-bold text-gray-900">LKR <?php echo number_format($product['price'], 2); ?></span>
                                </div>

                                <form action="add_to_cart.php" method="POST" class="w-full">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="w-full px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        Add to Cart
                                        <svg class="inline-block rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?php echo ($currentPage - 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="pagination-button bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Previous
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="pagination-button <?php echo $i === $currentPage ? 'bg-blue-700 text-white' : 'bg-gray-200 text-gray-700'; ?> hover:bg-gray-300">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?php echo ($currentPage + 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="pagination-button bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Next
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>