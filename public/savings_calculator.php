<?php
require_once 'classes/SavingsCalculator.php';
require_once '../includes/session_manager.php';
$sessionManager = new SessionManager();

// Process form submission
if (isset($_POST['calculateBtn'])) {
    try {
        $calculator = new SavingsCalculator();
        $results = $calculator->calculateSystem(
            floatval($_POST['monthly_bill']), 
            intval($_POST['phase_count'])
        );
        $_SESSION['results'] = $results;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: savings_calculator.php');
        exit;
    }
}

// Check if we should show results
$showCustomDiv = isset($_POST['calculateBtn']) && isset($_SESSION['results']);
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
    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8">Savings Calculator</p>
        <div class="pb-8">
            <a href="http://127.0.0.1/NPH_Solar_Solutions/public/Home_Page.php"
               class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Return To Home 
            </a>
        </div>
    </div>

    <div class="bg-gray-100 p-8 mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
        <form method="POST" action="savings_calculator.php">
            <p class="font-sans text-black pb-2">What is your monthly electricity bill?</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   placeholder="monthly bill" type="text" name="monthly_bill"/>

            <p class="font-sans text-black pb-2 pt-2">Phase count?</p>
            <select class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                    name="phase_count">
                <option value="0">Select Phase</option>
                <option value="1">Single Phase</option>
                <option value="3">Three Phase</option>
            </select>
            
            <div class="mt-6">
                <button type="submit" name="calculateBtn"
                        class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Calculate
                </button>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-screen-xl mx-auto mt-4" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($showCustomDiv): 
            $results = $_SESSION['results'];
        ?>
        <div class="custom1 mt-6 grid grid-cols-2 gap-4">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Recommended solar system for you</p>
                <p class="font-sans text-black pb-2"><?php echo $results['recommended_system']; ?> kW</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Average Monthly Generation</p>
                <p class="font-sans text-black pb-2"><?php echo $results['monthly_generation']; ?> Units</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Average Cost</p>
                <p class="font-sans text-black pb-2"><?php echo number_format($results['estimated_cost']); ?> LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Monthly Unit Used</p>
                <p class="font-sans text-black pb-2"><?php echo $results['units_consumed']; ?> Units</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Electricity Bill</p>
                <p class="font-sans text-black pb-2"><?php echo number_format($results['monthly_bill']); ?> LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Solar Monthly Installment</p>
                <p class="font-sans text-black pb-2"><?php echo number_format($results['solar_monthly_installment']); ?> LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Monthly Savings</p>
                <p class="font-sans text-black pb-2"><?php echo number_format($results['monthly_savings']); ?> LKR</p>
            </div>
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2">Make an Appointment</p>
                <a href="http://127.0.0.1/NPH_Solar_Solutions/public/appoinment.php">
                    <button type="button"
                            class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                        Appointment 
                    </button>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>