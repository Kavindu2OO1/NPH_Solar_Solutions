<?php
session_start();
error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 

// Set default timezone (adjust to your location)
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
        $greeting = "Good night";
    }
}
?>


<?php 
  include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NPH Solar Solutions</title>
    

    <link rel="stylesheet" href="style.css">
    
  

    <style>
        /* HTML: <div class="loader"></div> */
        .dropdown{
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

        footer{
          background-color: #333;
          color: white;
          text-align: center;
          padding: 0px;
        }
        .grid {
    padding: 1rem;
        }

    </style>

    
</head>
<body>
  

<div class="text2 font-sans text-left font-bold tracking-wide bg-white   max-w-fit m-auto mt-16 sm:w-3/4 md:w-1/2 lg:w-1/3">
    <p class="font-sans text-black text-xl pl-1 pr-1 pb-1 sm:text-2xl md:text-3xl align-middle text-center">
        NPH Solar Solutions
    </p>
    <p class="font-sans text-orange-500 text-xl pl-1 pr-1 pb-1 sm:text-sm md:text-sm align-middle text-center ">
    Powering a Sustainable Future with Every Ray of Sunshine
    </p>
    
</div>


<div class="bg-white w-full h-auto p-4 m-auto mt-48 grid grid-cols-1 md:grid-cols-2 gap-4">
  <!-- Image Div -->
  <div class=" m-auto hidden md:block">
    <img 
      class="w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 max-w-full object-contain" 
      src="/NPH_Solar_Solutions/pictures/NPH_Logo.jpg" 
      alt="profile picture">
  </div>

  <!-- Text Div -->
  <div>
    <p class="font-sans mt-11 text-orange-500 text-2xl sm:text-xl md:text-2xl lg:text-3xl font-bold text-left">
      About Us
    </p>
    <p class="font-sans mt-11 mr-8 text-black text-base sm:text-sm md:text-base lg:text-lg xl:text-xl font-medium text-left leading-relaxed">
      At NPH Solar Solutions, we are passionate about harnessing the power of the sun to create sustainable, 
      cost-effective energy solutions for homes and businesses. As a trusted provider of solar energy systems, 
      we specialize in designing, installing, and maintaining solar power solutions that help our clients reduce 
      their energy costs while contributing to a cleaner, greener planet.
    </p>
  </div>
</div>

<div class="bg-slate-200 pb-16 w-full h-auto p-4 m-auto pt-8">
    <!-- Heading Section -->
    <p class="font-sans mt-8 text-center text-orange-500 text-2xl sm:text-xl md:text-2xl lg:text-3xl font-bold">
        Our Services
    </p>

    <!-- Grid Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
      
        <!-- Service 1 -->
        <div class="shadow-lg bg-white rounded-lg m-auto flex flex-col justify-between min-h-[350px]">
            <img class="w-full h-auto scale-75 max-w-full object-contain mx-auto"
                src="/NPH_Solar_Solutions/pictures/on-grid.jpg"
                alt="profile picture">
            <p class="font-sans mt-8 mb-8 text-center border-cyan-300 text-black text-base sm:text-sm md:text-base lg:text-lg xl:text-xl font-medium leading-relaxed">
                On-Grid and Off-grid Solar System Installation
            </p>
            <p class="m-5 font-normal text-black flex-grow">
                We provide installation of on-grid solar systems, fit to industry standards and ensuring all work is carried out by certified technicians.
            </p>
        </div>
        
        <!-- Service 2 -->
        <div class="shadow-lg bg-white rounded-lg m-auto flex flex-col justify-between min-h-[350px]">
            <img class="w-full h-auto scale-75 max-w-full object-contain mx-auto"
                src="/NPH_Solar_Solutions/pictures/upgrade.jpg"
                alt="profile picture">
            <p class="font-sans mt-8 mb-8 text-center border-cyan-300 text-black text-base sm:text-sm md:text-base lg:text-lg xl:text-xl font-medium leading-relaxed">
                System Upgrades
            </p>
            <p class="m-5 font-normal text-black flex-grow">
                We specialize in upgrading existing solar systems, enhancing their performance and integrating the latest technology to meet your evolving energy needs.
            </p>
        </div>

        <!-- Service 3 -->
        <div class="shadow-lg bg-white rounded-lg m-auto flex flex-col justify-between min-h-[350px]">
            <img class="w-full h-auto scale-75 max-w-full object-contain mx-auto"
                src="/NPH_Solar_Solutions/pictures/import.jpg"
                alt="profile picture">
            <p class="font-sans mt-8 mb-8 text-center border-cyan-300 text-black text-base sm:text-sm md:text-base lg:text-lg xl:text-xl font-medium leading-relaxed">
                Solar Equipment Importation
            </p>
            <p class="m-5 font-normal text-black flex-grow">
                We import high-quality inverters, solar panels, and hybrid batteries from trusted manufacturers, ensuring the best products for your solar energy solutions.
            </p>
        </div>

        <!-- Service 4 -->
        <div class="shadow-lg bg-white rounded-lg m-auto flex flex-col justify-between min-h-[350px]">
            <img class="w-full h-auto scale-75 max-w-full object-contain mx-auto"
                src="/NPH_Solar_Solutions/pictures/maintain.jpg"
                alt="profile picture">
            <p class="font-sans mt-8 mb-8 text-center border-cyan-300 text-black text-base sm:text-sm md:text-base lg:text-lg xl:text-xl font-medium leading-relaxed">
                Maintenance and Repair Services
            </p>
            <p class="m-5 font-normal text-black flex-grow">
                Our team offers regular maintenance and repair services for solar energy systems, ensuring their long-term efficiency, reliability, and optimal performance.
            </p>
        </div>
    </div>
</div>



<div class="bg-white w-full h-auto p-4 mb-48 m-auto pt-8">
    <!-- Heading Section -->
    <p class="font-sans mt-11 text-center text-orange-500 text-2xl sm:text-xl md:text-2xl lg:text-3xl font-bold">
        Choose the Right Solar System For You 
    <p>
    <!-- Grid Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
        <!-- Service 1 -->
        <div class=" rounded-lg m-auto">
            <img 
                class="w-64 h-64 w ml-5 md:w-80 md:h-80 lg:w-96 lg:h-96 max-w-full object-contain" 
                src="/NPH_Solar_Solutions/pictures/calculator.png" 
                alt="Calculator">
                
        </div>
        
        <!-- Service 2 -->
        <div class="rounded-lg m-auto">
              <p class="m-5 font-normal text-black">
                Our Solar Savings Calculator is designed to help you find the perfect solar system for your needs. Simply input your monthly electricity bill, and the calculator will recommend the most suitable solar system for your energy consumption. It will also provide an estimate of your monthly savings, empowering you to make informed decisions about switching to clean, renewable energy while reducing your utility costs.
              </p>
          <div class="flex justify-center items-center mt-6">
              <a href="http://127.0.0.1/NPH_Solar_Solutions/public/savings_calculator.php" 
                class="content-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Find the Right System for You
              </a>
          </div>
        </div>

        
        
    </div>
</div>















<?php 
  include 'footer.html';
?>


</body>
</html>