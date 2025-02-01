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
$ID_Number = "";
$password = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $ID_Number = $_POST['ID_Number'];
    $password = $_POST['password'];

    // Check ID_Number for redirection
    if ($ID_Number == "2001" && $password == "1234") {
        // Redirect to the savings calculator
        header('Location: http://127.0.0.1/NPH_Solar_Solutions/public/Home_Page.php');
        exit();
    }

    // Check ID_Number for redirection
    if ($ID_Number == "2002" && $password == "1234") {
        // Redirect to the savings calculator
        header('Location: http://127.0.0.1/NPH_Solar_Solutions/public/adminpanel.php');
        exit();
    }


}

// Check if the form has been submitted
$showCustomDiv = isset($_POST['calculateBtn']);
?>


    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle ">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8 ">Sign In </p>

        <!-- redirect to home -->
        <div class="pb-8">
            <a
                href="http://127.0.0.1/NPH_Solar_Solutions/public/Home_Page.php"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Return To Home 
            </a>
        </div>
    </div>

    <div class="bg-gray-100 w-96 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
        <form method="POST" action="">
            <p class="font-sans text-black pb-2 text-left text-2xl">Sign In To your account </p>
            <p class="font-sans text-black pb-2 text-left">ID Number</p>
            <input class="placeholder:text-slate-400 block bg-white w-80 border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="ID Number" type="text" name="ID_Number"/>
            <p class="font-sans text-black pb-2 text-left">Password</p>
            <input class="placeholder:text-slate-400 block bg-white w-80 border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Password" type="text" name="password"/>

            
            <div class="mt-6">
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle w-80">
                    Sign In
                </button>
            </div>
            <p class="font-sans text-black pb-2 text-left mt-4">
                    Don't have an account yet? 
                    <a 
                        href="http://127.0.0.1/NPH_Solar_Solutions/public/register.php" 
                        class="text-blue-500 hover:underline"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Sign up
                    </a>
                    </p>

        </form>

        <?php if ($showCustomDiv): ?>
        <div class="custom1 mt-6 grid grid-cols-1 gap-4 ">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2 ">Name:<?php echo "id is" . $ID_Number; ?></p>
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
