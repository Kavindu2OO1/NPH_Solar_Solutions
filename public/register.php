<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        } elseif (!preg_match('/^\d{10,12}$/', $nic)) {
            $errors['nic'] = "Invalid ID Number format (10-12 digits)";
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
            
            $stmt = $conn->prepare("INSERT INTO users (
                first_name, 
                last_name, 
                id_number, 
                phone_number, 
                phase_count, 
                user_type, 
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

    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle ">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8 ">Register</p>

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
            <!-- First Name -->
            <p class="font-sans text-black pb-2">First Name</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm"
                placeholder="First name"
                type="text"
                name="fname"
                value="<?= $old_input['fname'] ?? '' ?>"
                required>
                <?php if (isset($errors['fname'])): ?>
                <div class="error"><?= $errors['fname'] ?></div>
                <?php endif; ?>
            
            
            <!-- Last Name -->
            <p class="font-sans text-black pb-2 pt-4">Last Name</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="text" 
                   name="lname"
                   value="<?= $old_input['lname'] ?? '' ?>"
                   required>
            <?php if (isset($errors['lname'])): ?>
                <div class="error"><?= $errors['lname'] ?></div>
            <?php endif; ?>

            <!-- ID Number -->
            <p class="font-sans text-black pb-2 pt-4">ID Number</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="text" 
                   name="nic"
                   pattern="\d{10,12}"
                   value="<?= $old_input['nic'] ?? '' ?>"
                   required>
            <?php if (isset($errors['nic'])): ?>
                <div class="error"><?= $errors['nic'] ?></div>
            <?php endif; ?>

            <!-- Password -->
            <p class="font-sans text-black pb-2 pt-4">Password</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="password" 
                   name="password"
                   minlength="8"
                   required>
            <?php if (isset($errors['password'])): ?>
                <div class="error"><?= $errors['password'] ?></div>
            <?php endif; ?>

            <!-- Phone Number -->
            <p class="font-sans text-black pb-2 pt-4">Phone Number</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="tel" 
                   name="phone"
                   pattern="\d{10}"
                   value="<?= $old_input['phone'] ?? '' ?>"
                   required>
            <?php if (isset($errors['phone'])): ?>
                <div class="error"><?= $errors['phone'] ?></div>
            <?php endif; ?>

            <!-- Phase Count -->
            <p class="font-sans text-black pb-2 pt-4">Phase Count</p>
            <select class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
            name="phase_count" required>
                <option value="0" <?= ($old_input['phase_count'] ?? 0) == 0 ? 'selected' : '' ?>>Select Phase</option>
                <option value="1" <?= ($old_input['phase_count'] ?? 0) == 1 ? 'selected' : '' ?>>Single Phase</option>
                <option value="3" <?= ($old_input['phase_count'] ?? 0) == 3 ? 'selected' : '' ?>>Three Phase</option>
            </select>
            <?php if (isset($errors['phase_count'])): ?>
                <div class="error"><?= $errors['phase_count'] ?></div>
            <?php endif; ?>

            <!-- Property Type -->
            <p class="font-sans text-black pb-2 pt-4">Property Type</p>
            <!-- Change the property_type select options to -->
            <select class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm"
            name="property_type" required>
                <option value="" <?= empty($old_input['property_type']) ? 'selected' : '' ?>>Select Type</option>
                <option value="0" <?= ($old_input['property_type'] ?? '') === 'Residential' ? 'selected' : '' ?>>Residential</option>
                <option value="1" <?= ($old_input['property_type'] ?? '') === 'Commercial' ? 'selected' : '' ?>>Commercial</option>
            </select>
            <?php if (isset($errors['property_type'])): ?>
                <div class="error"><?= $errors['property_type'] ?></div>
            <?php endif; ?>

            <!-- Address -->
            <p class="font-sans text-black pb-2 pt-4">Address</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="text" 
                   name="address"
                   value="<?= $old_input['address'] ?? '' ?>"
                   required>
            <?php if (isset($errors['address'])): ?>
                <div class="error"><?= $errors['address'] ?></div>
            <?php endif; ?>

            <!-- City -->
            <p class="font-sans text-black pb-2 pt-4">City</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="text" 
                   name="city"
                   value="<?= $old_input['city'] ?? '' ?>"
                   required>
            <?php if (isset($errors['city'])): ?>
                <div class="error"><?= $errors['city'] ?></div>
            <?php endif; ?>

            <!-- Postal Code -->
            <p class="font-sans text-black pb-2 pt-4">Postal Code</p>
            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" 
                   type="text" 
                   name="postal_code"
                   pattern="\d{5}"
                   maxlength="5"
                   value="<?= $old_input['postal_code'] ?? '' ?>"
                   required>
            <?php if (isset($errors['postal_code'])): ?>
                <div class="error"><?= $errors['postal_code'] ?></div>
            <?php endif; ?>
            
            <div class="mt-6">
                <button
                    type="submit"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                    Register
                </button>
            </div>
        </form>

        
        
        
        <?php if (isset($showSuccess) && $showSuccess): ?>
            <div class="custom1 mt-6 grid grid-cols-1 gap-4">
                <div class="shadow-lg rounded bg-white p-4">
                    <p class="font-sans text-black pb-2">welcome <?php echo $fname  ?> Registration Successful</p>
                </div>
                <div class="pb-8">
            <a
                href="http://127.0.0.1/NPH_Solar_Solutions/public/Home_Page.php"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Return To Home 
            </a>
        </div>
            </div>
        <?php endif; ?>
        

    </div>
    

    <?php 
        include 'footer.html';
    ?>


    
</body>
</html>
