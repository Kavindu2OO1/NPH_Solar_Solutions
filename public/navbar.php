<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
// Restrict access to only Admin role


//session_start();
error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 
// Set default timezone (adjust to your location)
date_default_timezone_set('Asia/Colombo');

// Initialize greeting
$greeting = "";
$currentHour = date('G');

// Check if user is logged in using SessionManager
if ($sessionManager->isLoggedIn()) {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NPH</title>
    <link rel="stylesheet" href="style.css">
    <script src="navscript.js" defer></script> 
    <style>
        .dropdown {
            padding-top: 5px;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 10px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 10px 0px;
            z-index: 1;
            border-radius: 2px;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }

        #signinmenu{
          display: none;
        }

        
        

    </style>
</head>
<body>
  <nav class="bg-white-800 border-1 border-black-1000">
    <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8 pt-16">
      <div class="relative flex h-16 items-center justify-between bg-white text-black p-4">
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <button id="mobile-menu-button" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-green-700 hover:text-green focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
          </button>
        </div>
        <div class="flex flex-1 mx-auto items-center justify-center sm:items-stretch sm:justify-start pt-3">
          <div class="flex shrink-0 items-center">
              <a href="http://127.0.0.1/NPH_Solar_Solutions/public/Home_Page.php" class="flex shrink-0 items-center">
                  <img class="h-13 w-12" src="/NPH_Solar_Solutions/pictures/NPH_Logo1.jpg" alt="NPH">
              </a>
          </div>
          <div class="hidden sm:block mx-auto flex justify-center items-center">
            <div class="flex space-x-4">
              
              
                <a href="http://127.0.0.1/NPH_Solar_Solutions/public/product.php" class="rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Products</a>
                
              
              <div class="dropdown">
                <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Projects</a>
                <div class="dropdown-content">
                  <a href="http://127.0.0.1/NPH_Solar_Solutions/public/residental_projects.php" class="rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Residental</a></br>
                  <a href="http://127.0.0.1/NPH_Solar_Solutions/public/commercial_projects.php" class="rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Commercial</a>
                </div>
              </div>
              <a href="http://127.0.0.1/NPH_Solar_Solutions/public/Progress.php" class="rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Progress</a>
              
              <a href="http://127.0.0.1/NPH_Solar_Solutions/public/savings_calculator.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Find the Right System for you
              </a>
              <?php if ($sessionManager->isLoggedIn()): ?>
                  <a href="logout.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                      Logout
                  </a>
              <?php endif; ?>

              <?php if ($sessionManager->getUserType() === 'Admin' ): ?>
                  <a href="adminpanel.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                      Admin Panel
                  </a>
              <?php endif; ?>

              <?php if ($sessionManager->getUserType() === 'Delivery_Personnel' ): ?>
                  <a href="http://127.0.0.1/NPH_Solar_Solutions/public/vieworders.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                      View Orders
                  </a>
              <?php endif; ?>

              

              <?php if ($sessionManager->isLoggedIn()): ?>
                  <div class="pl-2 rounded-md px-3 py-2 text-sm font-medium text-black-300">
                      <?php 
                          echo htmlspecialchars($greeting) . ', ';
                          echo '<span class="user-name">' . htmlspecialchars($_SESSION['first_name']) . '</span>.'; 
                      ?>
                  </div>
              <?php endif; ?>


              

              


            </div>
          </div>
        </div>
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
          <a href="http://127.0.0.1/NPH_Solar_Solutions/public/shoppingcart.php" 
              class="relative rounded-full bg-white p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
              <span class="sr-only">View notifications</span>
              <img src="/NPH_Solar_Solutions/pictures/shopping-cart.png" alt="Icon" class="size-6">
          </a>
          
          <div class="relative ml-3">
            <div>
            <?php
            $userId = (int)$sessionManager->getUserId();

            if ($userId !== 0): ?>
            <a
                  href="http://127.0.0.1/NPH_Solar_Solutions/public/userprofile.php"
                  class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <img class="size-8 rounded-full" src="/NPH_Solar_Solutions/pictures/profile_picture.jpg" alt="profile picture">
              </a>

            <?php else: ?>
              <a
                  href="http://127.0.0.1/NPH_Solar_Solutions/public/signin.php"
                  class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <img class="size-8 rounded-full" src="/NPH_Solar_Solutions/pictures/profile_picture.jpg" alt="profile picture">
              </a>
            <?php endif; ?>    
              
            </div>
            <div id="signinmenu"  class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem">Sign In</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="sm:hidden" id="mobile-menu" style="display: none;">
      <div class="space-y-1 px-2 pb-3 pt-2">
          <a href="http://127.0.0.1/NPH_Solar_Solutions/public/product.php" class="block rounded-md px-3 py-2 text-sm font-medium text-black-300 bg-white hover:text-green-700">Products</a>
          
          <!-- Projects dropdown -->
          <div class="relative">
              <button onclick="toggleDropdown()" class="flex items-center justify-between w-full text-left rounded-md px-3 py-2 text-sm font-medium text-black-300 bg-white hover:text-green-700">
                  Projects ˅
                  
              </button>
              <div id="projects-dropdown" class="hidden mt-1 space-y-1 bg-white rounded-md shadow">
                  <a href="http://127.0.0.1/NPH_Solar_Solutions/public/residental_projects.php" class="block rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Residential Projects</a>
                  <a href="http://127.0.0.1/NPH_Solar_Solutions/public/commercial_projects.php" class="block rounded-md px-3 py-2 text-sm font-medium text-black-300 hover:text-green-700">Commercial Projects</a>
              </div>
          </div>
  
          <a href="http://127.0.0.1/NPH_Solar_Solutions/public/Progress.php" class="block rounded-md px-3 py-2 text-sm font-medium text-black-300 bg-white hover:text-green-700">Progress</a>
          <a href="http://127.0.0.1/NPH_Solar_Solutions/public/savings_calculator.php" class="block rounded-md px-3 py-2 text-sm font-medium text-black-300 bg-white hover:text-green-700">Savings Calculator</a>
      </div>
  </div>
  </div>
  </nav>


  <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('projects-dropdown');
        dropdown.classList.toggle('hidden');
    }
</script>


</body>
</html>
