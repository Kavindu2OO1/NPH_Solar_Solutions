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

    <div class="bg-gray-100 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
        <form method="POST" action="">
            <p class="font-sans text-black pb-2">View Orders</p>

            

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
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="monthly bill" type="text" name="monthly_bill"/>

            <p class="font-sans text-black pb-2 pt-2">Status</p>
            <select id="country" class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Select Phase" type="text" name="phase_count" name="country">
                <option value="0">Select option</option>
                <option value="0">Approved</option>
                <option value="1">Pending</option>
                <option value="3">Under review</option>
                
            </select>
            <div class="mt-6">
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Update Status
                </button>
            </div>
        </form>

        <?php if ($showCustomDiv): ?>
        <div class="custom1 mt-6 grid grid-cols-2 gap-4 ">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2 ">Recommended solar system for you</p>
                <p class="font-sans text-black pb-2"> 5 kW</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4" >
                <p class="font-sans text-black pb-2">Average Monthly Generation</p>
                <p class="font-sans text-black pb-2"> 289 Units</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Average Cost</p>
                <p class="font-sans text-black pb-2"> 120,000 LKR</p>
            </div> 
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Monthly Unit Used</p>
                <p class="font-sans text-black pb-2">150 Units</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Electricity Bill</p>
                <p class="font-sans text-black pb-2">10000 LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Solar Monthly Installment</p>
                <p class="font-sans text-black pb-2">12,000 LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Monthly Savings</p>
                <p class="font-sans text-black pb-2">5000 LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
            <p class="font-sans text-black pb-2">Make an appoinment</p>
            <a href >
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    appoinment
                </button>
            </a>
            </div>
        </div>
        <?php endif; ?>

    </div>
    

    

    <?php 
        include 'footer.html';
    ?>

    
</body>
</html>
