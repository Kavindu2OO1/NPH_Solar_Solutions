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

    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle ">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8 ">Project Progress</p>

        <!-- redirect to home -->
        <div class="pb-8">
            <a
                href="http://127.0.0.1/NPH_Solar_Solutions/public/Home_Page.php"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Return To Home 
            </a>
        </div>
    </div>

    <div class="bg-gray-100 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
        <form method="POST" action="">
            <p class="font-sans text-red-600 pb-2">* Check your project progress</p>
            <p class="font-sans text-black pb-2">Enter Your ID Number</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="ID Number" type="text" name="ID_Number"/>

            
            <div class="mt-6">
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Check Progress
                </button>
            </div>
        </form>

        <?php if ($showCustomDiv): ?>
        <div class="custom1 mt-6 grid grid-cols-1 gap-4 ">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2 ">Name: Kavindu Thushantha</p>
                <p class="font-sans text-black pb-2">Selected Solar System: 5 kW</p>
                <p class="font-sans text-black pb-2">Status: Approved</p>
            </div>
            
        </div>
        <?php endif; ?>

    </div>
    

    <?php 
        include 'footer.html';
    ?>
   



    
</body>
</html>
