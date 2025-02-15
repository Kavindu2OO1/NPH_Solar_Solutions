<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();




error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 

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

<?php
    require_once 'dB_Connection.php';
    session_start();

    $errors = [];
    $old_input = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize inputs
        $fname = trim($_POST['fname'] ?? '');
        $lname = trim($_POST['lname'] ?? '');
        $nic = trim($_POST['nic'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $phase_count = intval($_POST['phase_count'] ?? 0);
        $property_type = trim($_POST['property_type'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');

        // Store old input
        $old_input = [
            'fname' => htmlspecialchars($fname),
            'lname' => htmlspecialchars($lname),
            'nic' => htmlspecialchars($nic),
            'phone' => htmlspecialchars($phone),
            'phase_count' => $phase_count,
            'property_type' => htmlspecialchars($property_type),
            'address' => htmlspecialchars($address),
            'city' => htmlspecialchars($city),
            'postal_code' => htmlspecialchars($postal_code)
        ];

        // Validation
        if (empty($fname)) $errors['fname'] = "First Name is required";
        if (empty($lname)) $errors['lname'] = "Last Name is required";
        
        if (empty($nic)) {
            $errors['nic'] = "ID Number is required";
        } elseif (!preg_match('/^(\d{10,12}|\d{9}[A-Z])$/', $nic)) {
            $errors['nic'] = "Invalid ID Number format (10-12 digits or 9 digits followed by an uppercase letter)";
        }
    
        if (empty($phone)) {
            $errors['phone'] = "Phone Number is required";
        } elseif (!preg_match('/^\d{10}$/', $phone)) {
            $errors['phone'] = "Invalid phone number format (10 digits)";
        }

        if (empty($password)) {
            $errors['password'] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters";
        }

        if ($phase_count === 0) {
            $errors['phase_count'] = "Please select a phase";
        } else {
            $phase_count = ($phase_count == 1) ? 'Single Phase' : 'Three Phase';
        }

        
        if (!isset($_POST['property_type']) || !in_array($_POST['property_type'], ['0', '1'])) {
            $errors['property_type'] = "Please select property type";
        } else {
            $property_type = ($_POST['property_type'] === '0') ? 'Residential' : 'Commercial';
        }

        if (empty($address)) $errors['address'] = "Address is required";
        if (empty($city)) $errors['city'] = "City is required";
        
        if (empty($postal_code)) {
            $errors['postal_code'] = "Postal Code is required";
        } elseif (!preg_match('/^\d{5}$/', $postal_code)) {
            $errors['postal_code'] = "Invalid postal code (5 digits required)";
        }

        // Set default user type
        $user_type = 'User';

        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO appointments (
                first_name, 
                last_name, 
                id_number, 
                phone_number, 
                phase_count,  
                property_type, 
                address, 
                city, 
                postal_code, 
                password_hash
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param(
                    "sssssssssss",
                    $fname,
                    $lname,
                    $nic,
                    $phone,
                    $phase_count,
                    $user_type,
                    $property_type,
                    $address,
                    $city,
                    $postal_code,
                    $hashed_password
                );

                if ($stmt->execute()) {
                    $showSuccess = true;
                    $old_input = []; // Clear form on success
                } else {
                    $errors['database'] = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errors['database'] = "Database error: " . $conn->error;
            }
        }
        $conn->close();
    }
    ?>
</head>
<body>
    <?php 
        // Check if the form has been submitted
        $showCustomDiv = isset($_POST['calculateBtn']);
    ?>

    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle ">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8 ">Make an Appointment </p>

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
            <p class="font-sans text-black pb-2">* We will visit your home or commercial property to provide an estimated price tailored to your requirements. </p>
            <p class="font-sans text-black pb-2">First Name</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="First name" type="text" name="ID_Number"/>
            <p class="font-sans text-black pb-2">Last Name</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="last name" type="text" name="ID_Number"/>
            <p class="font-sans text-black pb-2">ID Number</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="id number" type="text" name="ID_Number"/>
            <p class="font-sans text-black pb-2">Phone Number</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="phone number" type="text" name="ID_Number"/>
            <p class="font-sans text-black pb-2 pt-2">Phase count?</p>
            <select id="country" class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Select Phase" type="text" name="phase_count" name="country">
                <option value="0">Select Phase</option>
                <option value="1">Single Phase</option>
                <option value="3">Three Phase</option>
            </select>
            <p class="font-sans text-black pb-2 pt-2">Residential or Commercial?</p>
            <select id="country" class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Select Phase" type="text" name="phase_count" name="country">
                <option value="0">Residential</option>
                <option value="1">Commercial</option>
            </select>
            <p class="font-sans text-black pb-2 pt-2">Address</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="address" type="text" name="ID_Number"/>
            <p class="font-sans text-black pb-2">Preferred Date</p>
                    <input 
                    class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                    type="date" 
                    name="preferred_date"
                    />
            <div class="mt-6">
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Send
                </button>
            </div>
        </form>

        <?php if ($showCustomDiv): ?>
        <div class="custom1 mt-6 grid grid-cols-1 gap-4 ">
            <div class="shadow-lg rounded bg-white p-4">
                <p class="font-sans text-black pb-2 ">Appointment  sent waiting for confirmation</p>
                <p class="font-sans text-black pb-2">Appointment  Details:</p>
                <p class="font-sans text-black pb-2">Appointment  Number: 001</p>
                <p class="font-sans text-black pb-2">Name: name</p>
                <p class="font-sans text-black pb-2">Id Number: ID</p>
                <p class="font-sans text-black pb-2">Status: waiting for confirmation</p>
            </div>
            
        </div>
        <?php endif; ?>

    </div>
    

    

    <?php 
        include 'footer.html';
    ?>

    
</body>
</html>
