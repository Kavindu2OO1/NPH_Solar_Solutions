<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
// Restrict access to only Admin role
$sessionManager->checkAccess(['Admin','Manager']);


error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 

?>


<?php 
  include 'navbar.php';
?>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', value: 1);


require_once 'dB_Connection.php';

// Handle form submission
$errors = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize inputs
        $id_product = trim($_POST['product_id'] ?? '');
        $product_name = trim($_POST['product_name'] ?? '');
        $discription = $_POST['discription'] ?? '';
        $price = trim($_POST['price'] ?? '');
        $image = $_POST['image'] ?? '';
        $stock = trim($_POST['stock'] ?? '');
        

        

        // Input validation
        if (empty($id_product)) throw new Exception("Product ID is required");
        if (empty($product_name)) throw new Exception("Product name is required");
        if (empty($discription)) throw new Exception("Product description is required");
        if (empty($image)) throw new Exception("Product description is required");
        if (empty($price) || !is_numeric($price)) throw new Exception("Valid price is required");
        if (empty($stock) || !is_numeric($price)) throw new Exception("Valid Stock count is required");

       

        if (isset($_POST['InsertBtn'])) {
            $insert_stmt = $conn->prepare("INSERT INTO products (id, name, description, price, image_url,stock) 
                                         VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("issssi", 
                $id_product, 
                $product_name, 
                $discription, 
                $price, 
                $image,
                $stock

            );

            if (!$insert_stmt->execute()) {
                throw new Exception("Insert failed: " . $insert_stmt->error);
            }
            $successMessage = "Poduct added successfully!";
        }

        if (isset($_POST['UpdateBtn'])) {
            $update_stmt = $conn->prepare("UPDATE products 
                                         SET name = ?, description = ?, price = ?, image_url = ?, stock = ?
                                         WHERE id = ? ");
            $update_stmt->bind_param("ssdsii", 
                $product_name, 
                $discription, 
                $price, 
                $image, 
                $stock,
                $id_product
            );

            if (!$update_stmt->execute()) {
                throw new Exception("Update failed: " . $update_stmt->error);
            }
            $successMessage = "Product updated successfully!";
        }

    } catch (Exception $e) {
        $errors['database'] = $e->getMessage();
    }

    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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

        
        body {
            background-image: url('/NPH_Solar_Solutions/pictures/solar-panels-roof-solar-cell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }


        

    </style>
</head>
<body>
    

    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6  mt-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle ">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle  ">Admin Panel</p>
    </div>

        <?php
        include 'admin_navbar.php'
        ?>

    <div class="bg-gray-100 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-6xl"> 
        <!-- Alert Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
        <form method="POST" action="">        
            <div class="relative overflow-x-auto mb-8">
            <?php
                require_once 'dB_Connection.php';

                // First Table - Users
                echo '<p class="font-sans text-justify text-black pb-2">Products</p>';
                echo '<div class="relative overflow-x-auto mb-8 " style="max-height: 300px; overflow-y: auto;" >';

                $sql_users = "SELECT * FROM products";
                if ($result_users = mysqli_query($conn, $sql_users)) {
                    if (mysqli_num_rows($result_users) > 0) {
                        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">';
                        echo '<thead class="text-xs uppercase bg-white text-black sticky top-0">';
                        echo "<tr>";
                        echo '<th scope="col" class="px-6 py-3">id</th>';
                        echo '<th scope="col" class="px-6 py-3">Name</th>';
                        echo '<th scope="col" class="px-6 py-3">Discription</th>';
                        echo '<th scope="col" class="px-6 py-3">Price</th>';
                        echo '<th scope="col" class="px-6 py-3">Image URL</th>';
                        echo '<th scope="col" class="px-6 py-3">Stock count</th>';
                        echo '<th scope="col" class="px-6 py-3">Action</th>';
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        while ($row = mysqli_fetch_array($result_users)) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['id']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['name']) . '</td>';
                            echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . 
                                htmlspecialchars($row['description']) . '</th>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['price']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['image_url']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['stock']) . '</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<a href="delete_product.php?id=' . $row['id'] . '" 
                                    onclick="return confirm(\'Are you sure you want to delete this user?\')"
                                    class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                                    Delete
                                </a>';
                            echo '</td>';
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        mysqli_free_result($result_users);
                    } else {
                        echo '<div class="text-center py-4 text-gray-500">No users found.</div>';
                    }
                }
                echo '</div>';


                // Close connection to db
                mysqli_close($conn);
                ?>
            </div>
            <p class="font-sans text-black pb-2">Product ID</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="product ID" type="text" name="product_id"/>
            
            <p class="font-sans text-black pb-2">Name</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="product name" type="text" name="product_name"/>
            <?php if (isset($errors['id_number'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= $errors['id_number'] ?></p>
            <?php endif; ?>
            <p class="font-sans text-black pb-2">Discription</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="product discription" type="text" name="discription"/>
            
            <p class="font-sans text-black pb-2 pt-2">Price</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="100.00" type="text" name="price"/>
            
            <p class="font-sans text-black pb-2 pt-2">Image</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Image path" type="text" name="image"/>
            
            
            <p class="font-sans text-black pb-2 pt-2">Stock </p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="0" type="text" name="stock"/>
            
            <div class="mt-6">
                <button
                    type="submit"
                    name="InsertBtn"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Add Product
                </button>

                <button
                    type="submit"
                    name="UpdateBtn"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Update Product
                </button>

                <div class="pt-5" id="masg">
                    <?php if (!empty($successMessage)): ?>
                        <div class="bg-green-100 border   border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?= $successMessage ?></span>
                            
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border  border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <?php foreach ($errors as $error): ?>
                            <span class="block sm:inline"><?= $error ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php endif; ?>
                </div>
                        
            </div>
        </form>

        <?php if ($showCustomDiv): ?>
        <div class="custom1 mt-6 grid grid-cols-1 gap-4 ">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2 ">Project Status Updated Succesfull</p>
                
            </div>
            
        </div>
        <?php endif; ?>

    </div>
    
    

    <?php 
        include 'footer.html';
     ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
});
</script>
</body>
</html>
