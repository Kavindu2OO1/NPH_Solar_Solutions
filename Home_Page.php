<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
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
<nav class="bg-white-800 border-1 border-black-1000">
  
  <div class="mx-auto max-w-7xl  px-5 sm:px-6 lg:px-8  pt-16">
    <div class="relative flex h-16 items-center justify-between bg-white text-black p-4">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <!-- Mobile menu button-->
        <button type="button" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-green-700 hover:text-green focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>
          <!--
            Icon when menu is closed.

            Menu open: "hidden", Menu closed: "block"
          -->
          <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!--
            Icon when menu is open.

            Menu open: "block", Menu closed: "hidden"
          -->
          <svg class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex flex-1 mx-auto items-center justify-center sm:items-stretch sm:justify-start pt-3 ">
        <div class="flex shrink-0 items-center">
          <img class="h-13 w-12" src="pictures\NPH_Logo1.jpg" alt="NPH">
        </div>

        <div class="hidden sm:block mx-auto flex justify-center items-center">
  <div class="flex space-x-4">
  <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300  hover:text-green-700" aria-current="test">About</a>
    <div class="dropdown">
      <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300  hover:text-green-700">Packages</a>
      <div class="dropdown-content">
        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300  hover:text-green-700">Package 1</a>
      </div>
    </div>
    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300  hover:text-green-700">Projects</a>
    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300  hover:text-green-700">Progress</a>
    <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-black-300  hover:text-green-700">Contact Us</a>
    <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Savings calculator</button>
  </div>
</div>



      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <button type="button" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
          <span class="absolute -inset-1.5"></span>
          <span class="sr-only">View notifications</span>
          <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
          </svg>
        </button>

        <!-- Profile dropdown -->
        <div class="relative ml-3">
          <div>
            <button type="button" class="relative flex rounded-full bg-gray-800  text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
              <span class="absolute -inset-1.5"></span>
              <span class="sr-only">Open user menu</span>
              <img class="size-8 rounded-full" src="pictures/profile_picture.jpg" alt="profile picture">
            </button>
          </div>

          <!--
            Dropdown menu, show/hide based on menu state.

            Entering: "transition ease-out duration-100"
              From: "transform opacity-0 scale-95"
              To: "transform opacity-100 scale-100"
            Leaving: "transition ease-in duration-75"
              From: "transform opacity-100 scale-100"
              To: "transform opacity-0 scale-95"
          -->
              
          <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
            <!-- Active: "bg-gray-100 outline-none", Not Active: "" -->
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="sm:hidden" id="mobile-menu">
    <div class="space-y-1 px-2 pb-3 pt-2">
      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
      <a href="#" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Dashboard</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Team</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Projects</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Calendar</a>

    </div>
  </div>


</nav>


<!-- <img class="bbg-gradient-to-tl" src="pictures/solar-panels-roof-solar-cell.jpg" alt="profile picture"> -->
<h1> My name is kavindu</h1>



<footer>
        <p>NPH Solar Solution</p>
        <p><a href="mailto:hege@example.com">hege@example.com</a></p>
      </footer>






</body>
</html>