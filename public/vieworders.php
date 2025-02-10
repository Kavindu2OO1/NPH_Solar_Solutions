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



// Update session time on activity
$_SESSION['created'] = time();
?>

<?php 
  include 'navbar.php';
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
            <p class="font-sans text-black pb-2">Orders</p>

            

            <div class="relative overflow-x-auto mb-8">
                <table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">
                    <thead class="text-xs  uppercase  bg-white  text-black">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Item
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Phone Number
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Address
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Kavindu Thushantha Herath 
                            </th>
                            <td class="px-6 py-4">
                                id
                            </td>
                            <td class="px-6 py-4">
                                5 KW Solar On Grid System
                            </td>
                            <td class="px-6 py-4">
                                0712603722
                            </td>
                            <td class="px-6 py-4">
                                address
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Shanaya Hassen
                            </th>
                            <td class="px-6 py-4">
                                id
                            </td>
                            <td class="px-6 py-4">
                                25 KW solar On grid system
                            </td>
                            <td class="px-6 py-4">
                                0712854788
                            </td>
                            <td class="px-6 py-4">
                                address
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Manidu Lakmith
                            </th>
                            <td class="px-6 py-4">
                                id
                            </td>
                            <td class="px-6 py-4">
                                1KW Solar panel
                            </td>
                            <td class="px-6 py-4">
                                0718010456
                            </td>
                            <td class="px-6 py-4">
                                address
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="font-sans text-black pb-2">User ID</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Id" type="text" name="monthly_bill"/>

            
            <div class="mt-6">
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Delete Order
                </button>
            </div>
        </form>

        <?php if ($showCustomDiv): ?>
        <div class="custom1 mt-6 grid grid-cols-1 gap-4  ">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2 ">Order Succesfully Deleted</p>
                <p class="font-sans text-black pb-2">ID: 2001</p>
            </div>
            
        </div>
        <?php endif; ?>

    </div>
    

    

    <?php 
        include 'footer.html';
    ?>
    
</body>
</html>
