<?php 
include 'navbar.html';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>projects</title>
    <style>
    body {
        background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
    }

    nav,
    .content {
        position: relative;
        z-index: 10;
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
    </style>
</head>

<body>
<div class="font-sans text-left tracking-wide mt-20 ml-4 font-bold align-middle bg-white max-w-fit shadow-2xl">
    <p class="font-sans shadow-lg text-black text-3xl align-middle p-1">Commercial Projects Done by NPH Solar Solutions</p>
</div>

<div class="items1 bg-gray-100 mt-11 p-4 size-full grid grid-cols-2 gap-3">

    <!-- Card 1 -->
    <div class="relative shadow-lg rounded bg-white p-4 m-10">
        <img class="transition-all duration-300 rounded-lg hover:blur-sm" src="/xampp/NPH_Solar_Solutions/pictures/project1.jpg" alt="Project 1">
        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-lg">
            <p class="text-white text-lg font-bold">25 KW System</p>
            <p class="text-white text-sm mt-2 px-2 text-center">This project features a 5kW solar panel installation for a residential property.</p>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="relative shadow-lg rounded bg-white p-4 m-10">
        <img class="transition-all duration-300 rounded-lg hover:blur-sm" src="/xampp/NPH_Solar_Solutions/pictures/project2.jpg" alt="Project 2">
        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-lg">
            <p class="text-white text-lg font-bold">5 KW  System</p>
            <p class="text-white text-sm mt-2 px-2 text-center">This installation uses advanced solar technology for optimal energy generation.</p>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="relative shadow-lg rounded bg-white p-4 m-10">
        <img class="transition-all duration-300 rounded-lg hover:blur-sm" src="/xampp/NPH_Solar_Solutions/pictures/project3.jpg" alt="Project 3">
        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-lg">
            <p class="text-white text-lg font-bold">10 KW System</p>
            <p class="text-white text-sm mt-2 px-2 text-center">A customized solar solution for a modern residential villa.</p>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="relative shadow-lg rounded bg-white p-4 m-10">
        <img class="transition-all duration-300 rounded-lg hover:blur-sm" src="/xampp/NPH_Solar_Solutions/pictures/project1.jpg" alt="Project 4">
        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-lg">
            <p class="text-white text-lg font-bold">6 KW System</p>
            <p class="text-white text-sm mt-2 px-2 text-center">An eco-friendly solar panel setup tailored for urban households.</p>
        </div>
    </div>

</div>


<?php 
include 'footer.html';
?>
</body>

</html>