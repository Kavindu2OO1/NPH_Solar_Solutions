<?php

session_start();
error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 

// Restrict access to admins only
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'Admin') {
    header("HTTP/1.1 403 Forbidden");
    header("Location: Home_Page.php");
    exit();
}

// Admin session timeout (1 hour)
$admin_session_duration = 3600;
if (isset($_SESSION['created']) && (time() - $_SESSION['created'] > $admin_session_duration)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update session time on activity
$_SESSION['created'] = time();
?>

<?php 
  include 'navbar.php';
?>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'dB_Connection.php';

// Check if the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: Home_Page.php");
    exit();
}

// Handle form submission
$errors = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize inputs
        $id_number = trim($_POST['id_number'] ?? '');
        $project_name = trim($_POST['project_name'] ?? '');
        $phase_count = $_POST['phase_count'] ?? '0';
        $status = $_POST['status'] ?? '0';
        $price = trim($_POST['price'] ?? '');

        // Convert numeric values to meaningful strings
        $phase_mapping = [
            '0' => 'Single Phase',
            '3' => 'Three Phase'
        ];
        
        $status_mapping = [
            '1' => 'Pending',
            '2' => 'In Progress',
            '3' => 'Completed',
            '4' => 'Cancelled',

        ];

        // Input validation
        if (empty($id_number)) throw new Exception("National ID is required");
        if (empty($project_name)) throw new Exception("Project name is required");
        if ($phase_count === '0') throw new Exception("Please select a phase count");
        if ($status === '0') throw new Exception("Please select a status");
        if (empty($price) || !is_numeric($price)) throw new Exception("Valid price is required");

        // Get user ID
        $stmt = $conn->prepare("SELECT id FROM users WHERE id_number = ?");
        $stmt->bind_param("s", $id_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->num_rows) throw new Exception("User not found with provided ID");
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Map values to database-friendly formats
        $phase_value = $phase_mapping[$phase_count] ?? 'Single Phase';
        $status_value = $status_mapping[$status] ?? 'Pending';

        if (isset($_POST['InsertBtn'])) {
            $insert_stmt = $conn->prepare("INSERT INTO projects (user_id, project_name, phase_count, status, price) 
                                         VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("isssd", 
                $user_id, 
                $project_name, 
                $phase_value, 
                $status_value, 
                $price
            );

            if (!$insert_stmt->execute()) {
                throw new Exception("Insert failed: " . $insert_stmt->error);
            }
            $successMessage = "Project added successfully!";
        }

        if (isset($_POST['UpdateBtn'])) {
            $update_stmt = $conn->prepare("UPDATE projects 
                                         SET phase_count = ?, status = ?, price = ?
                                         WHERE user_id = ? AND project_name = ?");
            $update_stmt->bind_param("ssdis", 
                $phase_value, 
                $status_value, 
                $price, 
                $user_id, 
                $project_name
            );

            if (!$update_stmt->execute()) {
                throw new Exception("Update failed: " . $update_stmt->error);
            }
            $successMessage = "Project updated successfully!";
        }

    } catch (Exception $e) {
        $errors['database'] = $e->getMessage();
    } finally {
        $conn->close();
    }

    
}
?>

<script>
function scrollWin() {
  window.scrollTo(0, 500);
}
</script>
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

        nav, .content {
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
        <form method="POST" action="">
            <p class="font-sans text-justify  text-black pb-2">Users</p>

            

            <div class="relative overflow-x-auto mb-8">
            

            <?php
require_once 'dB_Connection.php';

// Prepare SQL statement to select all users
$sql = "SELECT * FROM users";

// Execute query
if ($result = mysqli_query($conn, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">';
        echo '<thead class="text-xs uppercase bg-white text-black">';
        echo "<tr>";
            echo '<th scope="col" class="px-6 py-3">First Name</th>';
            echo '<th scope="col" class="px-6 py-3">Last Name</th>';
            echo '<th scope="col" class="px-6 py-3">ID Number</th>';
            echo '<th scope="col" class="px-6 py-3">Phone Number</th>';
            echo '<th scope="col" class="px-6 py-3">Phase Count</th>';
            echo '<th scope="col" class="px-6 py-3">User Type</th>';
            echo '<th scope="col" class="px-6 py-3">Property Type</th>';
            echo '<th scope="col" class="px-6 py-3">Address</th>';
            echo '<th scope="col" class="px-6 py-3">City</th>';
            echo '<th scope="col" class="px-6 py-3">Postal Code</th>';
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . 
                     htmlspecialchars($row['first_name']) . '</th>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['last_name']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['id_number']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['phone_number']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['phase_count']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['user_type']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['property_type']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['address']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['city']) . '</td>';
                echo '<td class="px-6 py-4">' . htmlspecialchars($row['postal_code']) . '</td>';
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        
        // Free result set
        mysqli_free_result($result);
    } else {
        echo '<div class="text-center py-4 text-gray-500">No users found.</div>';
    }
} else {
    echo '<div class="text-center py-4 text-red-500">Error: ' . mysqli_error($conn) . '</div>';
}

// Close connection
mysqli_close($conn);
?>
            </div>
            <p class="font-sans text-justify  text-black pb-2">Project Details</p>
            <div class="relative overflow-x-auto mb-8">
                <table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">
                    <thead class="text-xs  uppercase  bg-white  text-black">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Phase
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Price
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Kavindu Thushantha Herath 
                            </th>
                            <td class="px-6 py-4">
                                3
                            </td>
                            <td class="px-6 py-4">
                                Pending
                            </td>
                            <td class="px-6 py-4">
                                LKR 1000000
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Shanaya Hassen
                            </th>
                            <td class="px-6 py-4">
                                3
                            </td>
                            <td class="px-6 py-4">
                                Approved
                            </td>
                            <td class="px-6 py-4">
                                LKR 3000000
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Manidu Lakmith
                            </th>
                            <td class="px-6 py-4">
                                1
                            </td>
                            <td class="px-6 py-4">
                                Under review 
                            </td>
                            <td class="px-6 py-4">
                                LKR 9000000
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            
            <p class="font-sans text-black pb-2">User ID</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="1234567890V" type="text" name="id_number"/>
            <?php if (isset($errors['id_number'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= $errors['id_number'] ?></p>
            <?php endif; ?>
            <p class="font-sans text-black pb-2">Project Name</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Wadduwa Project" type="text" name="project_name"/>
            <p class="font-sans text-black pb-2 pt-2">Phase Count</p>
            <select id="phase_count" class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Select Phase" type="text" name="phase_count" >
                <option value="0">Select option</option>
                <option value="1">Single Phase</option>
                <option value="3">Three Phase</option>
                
            </select>
            <p class="font-sans text-black pb-2 pt-2">Status</p>
            <select id="status" class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Select Phase" type="text" name="status">
                <option value="0">Select option</option>
                <option value="1">Pending</option>
                <option value="2">In Progress</option>
                <option value="3">Completed</option>
                <option value="4">Cancelled</option>
                
            </select>

            <p class="font-sans text-black pb-2">Price</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Rs.xxxxxx" type="text" name="price"/>


            <div class="mt-6">
                <button
                    type="submit"
                    name="InsertBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Insert Project
                </button>

                <button
                    type="submit"
                    name="UpdateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Update Project
                </button>

                <div class="pt-5" id="masg">
                <?php if (!empty($successMessage)): ?>
                    <script>
                    scrollWin();
                    </script>
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
    
</body>
</html>
