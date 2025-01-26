<?php 
  include 'navbar.html';
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

    <div class=" bg-slate-900 shadow-sm w-full  mt-8 pt-10 pb-10 justify-items-center ">
        <div >
        <a href="http://127.0.0.1/xampp/NPH_Solar_Solutions/public/vieworders.php"  
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            View Orders
        </a>

        <a href="http://127.0.0.1/xampp/NPH_Solar_Solutions/public/viewappoinments.php"   
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
             View Appoinments
        </a>

        <a href="http://127.0.0.1/xampp/NPH_Solar_Solutions/public/adminpanel.php" 
            class="text-white bg-blue-700 hover:bg-blue-800  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 m-3  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Set Progress
        </a>

        </div>
    </div>

    <div class="bg-gray-100 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
        <form method="POST" action="">
            <p class="font-sans text-black pb-2">Set Project Progress</p>

            

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
