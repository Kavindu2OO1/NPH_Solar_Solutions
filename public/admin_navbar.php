<?php

error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 


// Set Sri lanksn timezone 
date_default_timezone_set('Asia/Colombo');

// Initialize greeting
$greeting = "";
$currentHour = date('G');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Determine time-based greeting
    if ($currentHour >= 5 && $currentHour < 12) {
        $greeting = "Good morning";
    } elseif ($currentHour >= 12 && $currentHour < 18) {
        $greeting = "Good afternoon";
    } elseif ($currentHour >= 18 && $currentHour < 22) {
        $greeting = "Good evening";
    } else {
        $greeting = "Good day!";
    }
}

?>

<div class=" bg-slate-900 shadow-sm w-full   pb-10 justify-items-center ">
        <div class="pt-10">
        <a href="http://127.0.0.1/NPH_Solar_Solutions/public/vieworders.php"  
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            View Orders
        </a>

        <a href="http://127.0.0.1/NPH_Solar_Solutions/public/viewappoinments.php"   
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
             View Appoinments
        </a>

        <a href="http://127.0.0.1/NPH_Solar_Solutions/public/adminpanel.php" 
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Set Project Progress
        </a>

        <a href="../public/usermanagement.php" 
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            User Management
        </a>

        <a href="http://127.0.0.1/NPH_Solar_Solutions/public/reports.php" 
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Reports
        </a>

        <a href="http://127.0.0.1/NPH_Solar_Solutions/public/products.php" 
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Products
        </a>


       

        </div>
    </div>