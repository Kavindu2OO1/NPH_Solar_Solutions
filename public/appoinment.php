<?php
require_once '../includes/session_manager.php';
require_once 'dB_Connection.php';

$sessionManager = new SessionManager();

error_reporting(0); // Disable all error reporting
ini_set('display_errors', 0);

session_start();

$errors = [];
$old_input = [];
$showCustomDiv = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $phase_count = intval($_POST['phase_count'] ?? 0);
    $property_type = trim($_POST['property_type'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $preferred_date = trim($_POST['preferred_date'] ?? '');
    
    // Store old input
    $old_input = compact('fname', 'lname', 'nic', 'phone', 'phase_count', 'property_type', 'address', 'preferred_date');

    // Validation
    if (empty($fname)) $errors['fname'] = "First Name is required";
    if (empty($lname)) $errors['lname'] = "Last Name is required";
    if (empty($nic) || !preg_match('/^\d{10,12}$/', $nic)) {
        $errors['nic'] = "Invalid ID Number format";
    }
    if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
        $errors['phone'] = "Invalid phone number format";
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
    if (empty($preferred_date)) $errors['preferred_date'] = "Preferred Date is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO appointments (
            first_name, last_name, id_number, phone_number, phase_count, res_or_commer, address, preferred_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("ssssssss", 
                $fname, $lname, $nic, $phone, $phase_count, $property_type, $address, $preferred_date
            );
            
            if ($stmt->execute()) { 
                $showCustomDiv = true;
                $appointment_id = $stmt->insert_id;
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
    </style>
</head>
<body>
    <div class="font-sans text-center bg-slate-900 rounded pt-6 pb-8 font-bold">
        <p class="text-green-500 text-3xl">NPH Solar Solutions</p>
        <p class="text-orange-500 text-5xl pb-8">Make an Appointment</p>
        <a href="Home_Page.php" class="text-white bg-green-700 hover:bg-green-800 rounded-lg text-sm px-3 py-2">Return To Home</a>
    </div>

    <div class="bg-gray-100 p-8 mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl">
        <form method="POST" action="">
            <p class="text-black pb-2">First Name</p>
            <input class="w-full border rounded-md py-2 px-3" type="text" name="fname" value="<?php echo htmlspecialchars($old_input['fname'] ?? ''); ?>" required>
            
            <p class="text-black pb-2">Last Name</p>
            <input class="w-full border rounded-md py-2 px-3" type="text" name="lname" value="<?php echo htmlspecialchars($old_input['lname'] ?? ''); ?>" required>
            
            <p class="text-black pb-2">ID Number</p>
            <input class="w-full border rounded-md py-2 px-3" type="text" name="nic" value="<?php echo htmlspecialchars($old_input['nic'] ?? ''); ?>" required>
            
            <p class="text-black pb-2">Phone Number</p>
            <input class="w-full border rounded-md py-2 px-3" type="text" name="phone" value="<?php echo htmlspecialchars($old_input['phone'] ?? ''); ?>" required>
            
            <p class="text-black pb-2">Phase count?</p>
            <select class="w-full border rounded-md py-2 px-3" name="phase_count" required>
                <option value="0">Select Phase</option>
                <option value="1">Single Phase</option>
                <option value="3">Three Phase</option>
            </select>
            
            <p class="text-black pb-2">Residential or Commercial?</p>
            <select class="w-full border rounded-md py-2 px-3" name="property_type" required>
                <option value="0">Residential</option>
                <option value="1">Commercial</option>
            </select>
            
            <p class="text-black pb-2">Address</p>
            <input class="w-full border rounded-md py-2 px-3" type="text" name="address" required>
            
            <p class="text-black pb-2">Preferred Date</p>
            <input class="w-full border rounded-md py-2 px-3" type="date" name="preferred_date" required>
            
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg mt-4">Send</button>
        </form>

        <?php if ($showCustomDiv): ?>
        <div class="shadow-lg rounded bg-white p-4 mt-6">
            <p>Appointment sent, waiting for confirmation.</p>
            <p>Appointment Number: <?php echo $appointment_id; ?></p>
            <p>Name: <?php echo htmlspecialchars($fname . ' ' . $lname); ?></p>
            <p>ID Number: <?php echo htmlspecialchars($nic); ?></p>
            <p>Status: Waiting for confirmation</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
