<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
// Restrict access to only Admin role
$sessionManager->checkAccess(['Admin','Delivery_Personnel','Manager']);


error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 
?>

<?php 
  include 'navbar.php';
?>

<!-- get orders from db -->
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
        $id_number = trim($_POST['id_number'] ?? '');
        $status = $_POST['status'] ?? '0';

        // Convert number to string
        $status_mapping = [
            '1' => 'Pending',
            '2' => 'Paid',
            '3' => 'Shipped',
            '4' => 'Delivered',
            '5' => 'Cancelled',


        ];

        // Input validation
        if (empty($id_number)) throw new Exception("Order ID is required");
        if ($status === '0') throw new Exception("Please select a status");

        // Get user ID
        $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ?");
        $stmt->bind_param("s", $id_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->num_rows) throw new Exception("Order not found with provided ID");
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Map values to database-friendly formats
        $status_value = $status_mapping[$status] ?? 'Pending';


        if (isset($_POST['UpdateBtn'])) {
            $update_stmt = $conn->prepare("UPDATE orders 
                                         SET  status = ?
                                         WHERE id = ?");
            $update_stmt->bind_param("si", 
                $status_value, 
                $user_id, 

            );

            if (!$update_stmt->execute()) {
                throw new Exception("Update failed: " . $update_stmt->error);
            }
            $successMessage = "Order updated successfully!";
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
    <title>Savings Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        /* Background Styling */
        body {
            background-image: url('/NPH_Solar_Solutions/pictures/solar-panels-roof-solar-cell.jpg');
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
    <?php 
        // Check if the form has been submitted
        $showCustomDiv = isset($_POST['calculateBtn']);
    ?>

    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 mt-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle ">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle  ">Admin Panel</p>
    </div>

    <?php
        include 'admin_navbar.php'
    ?>

<div class="bg-gray-100 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
    <form method="POST" action="">      
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
            <div class="relative overflow-x-auto mb-8">
            <?php
                require_once 'dB_Connection.php';

                // First Table - Users
                echo '<p class="font-sans text-justify text-black pb-2">Orders</p>';
                echo '<div class="relative overflow-x-auto mb-8 " style="max-height: 300px; overflow-y: auto;" >';

                $sql_users = "SELECT * FROM orders";
                if ($result_users = mysqli_query($conn, $sql_users)) {
                    if (mysqli_num_rows($result_users) > 0) {
                        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">';
                        echo '<thead class="text-xs uppercase bg-white text-black sticky top-0">';
                        echo "<tr>";
                        echo '<th scope="col" class="px-6 py-3">Order ID</th>';
                        echo '<th scope="col" class="px-6 py-3">User ID</th>';
                        echo '<th scope="col" class="px-6 py-3">Total Price</th>';
                        echo '<th scope="col" class="px-6 py-3">Status</th>';
                        echo '<th scope="col" class="px-6 py-3">Customer Information</th>';
                        echo '<th scope="col" class="px-6 py-3">Created at</th>';
                        echo '<th scope="col" class="px-6 py-3">Action</th>';
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        while ($row = mysqli_fetch_array($result_users)) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['id']) . '</td>';
                            echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . 
                                htmlspecialchars($row['user_id']) . '</th>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['total_price']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['status']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['customer_info']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['created_at']) . '</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<a href="delete_order.php?id=' . $row['id'] . '" 
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

                // First Table - Users
                echo '<p class="font-sans text-justify text-black pb-2">Order Items</p>';
                echo '<div class="relative overflow-x-auto mb-8 " style="max-height: 300px; overflow-y: auto;" >';

                $sql_users = "SELECT * FROM order_items";
                if ($result_users = mysqli_query($conn, $sql_users)) {
                    if (mysqli_num_rows($result_users) > 0) {
                        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">';
                        echo '<thead class="text-xs uppercase bg-white text-black sticky top-0">';
                        echo "<tr>";
                        echo '<th scope="col" class="px-6 py-3">Order ID</th>';
                        echo '<th scope="col" class="px-6 py-3">Product ID</th>';
                        echo '<th scope="col" class="px-6 py-3">Quantity</th>';
                        echo '<th scope="col" class="px-6 py-3">Price</th>';
                        echo '<th scope="col" class="px-6 py-3">Action</th>';
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        while ($row = mysqli_fetch_array($result_users)) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['order_id']) . '</td>';
                            echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . 
                                htmlspecialchars($row['product_id']) . '</th>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['quantity']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['price']) . '</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<a href="delete_order_items.php?id=' . $row['id'] . '" 
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
            <p class="font-sans text-black pb-2">Order ID</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="id" type="text" name="id_number"/>
            <?php if (isset($errors['id_number'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= $errors['id_number'] ?></p>
            <?php endif; ?>
            <p class="font-sans text-black pb-2 pt-2">Status</p>
            <select id="country" class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Select Phase" type="text" name="status">
                <option value="0">Select status</option>
                <option value="1">Pending</option>
                <option value="2">Paid</option>
                <option value="3">Shipped</option>
                <option value="4">Delivered</option>
                <option value="5">Cancelled</option>
                
            </select>
            
            <div class="mt-6">
                <button
                    type="submit"
                    name="UpdateBtn"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Update Order
                </button>
            </div>
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
        </form>

        
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
