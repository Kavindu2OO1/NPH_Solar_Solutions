<?php 
  include 'navbar.html';
?>

<!DOCTYPE html>
<html lang="en">
<head >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
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
            background-image: url('/xampp/NPH_Solar_Solutions/pictures/solar-panels-roof-solar-cell.jpg');
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
          padding: 100px;
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


<div class="bg-white w-full h-auto p-4 m-auto  mt-48   grid grid-cols-2 gap-2 ">

<div class="shadow-lg m-auto ">
  <img class="w-48 h-48 md:w-32 lg:w-48" src="/xampp/NPH_Solar_Solutions/pictures/NPH_Logo.jpg" alt="profile picture">
</div>

<div>

    <p class="font-sans text-orange-500 text-xl pl-1 pr-1 pb-1 mt-8 sm:text-lg md:text-lgs align-left text-left font-bold ">
      About Us
    </p>


    <p class="font-sans text-black  pl-1 pr-1 pb-1 mr-48 mt-8 sm:text-lg md:text-lgs align-left text-left font-semibold  text-wrap"> At NPH Solar Solutions, we are passionate about harnessing the power of the sun to create sustainable, cost-effective energy solutions for homes and businesses. As a trusted provider of solar energy systems, we specialize in designing, installing, and maintaining solar power solutions that help our clients reduce 
      their energy costs while contributing to a cleaner, greener planet.
    </p>

  </div>
    
</div>

















</body>
</html>