<?php
require_once '../includes/session_manager.php';
$sessionManager = new SessionManager();
$sessionManager->checkAccess(['Admin']);

error_reporting(0);
ini_set('display_errors', 0);

include 'navbar.php';
require_once 'dB_Connection.php';

// Handle form submission
$errors = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? '';
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $id_number = trim($_POST['id_number'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $phase_count = $_POST['phase_count'] ?? '';
        $user_type = $_POST['user_type'] ?? '';
        $property_type = $_POST['property_type'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');

        // Validation
        if (empty($first_name)) throw new Exception("First name is required");
        if (empty($last_name)) throw new Exception("Last name is required");
        if (empty($id_number)) throw new Exception("ID number is required");
        if (empty($phone_number)) throw new Exception("Phone number is required");

        if (isset($_POST['InsertBtn'])) {
            // Check if ID number exists
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE id_number = ?");
            $check_stmt->bind_param("s", $id_number);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                throw new Exception("User with this ID number already exists");
            }

            $insert_stmt = $conn->prepare("INSERT INTO users 
                (first_name, last_name, id_number, phone_number, phase_count, user_type, property_type, address, city, postal_code)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $insert_stmt->bind_param("ssssssssss", 
                $first_name, $last_name, $id_number, $phone_number, 
                $phase_count, $user_type, $property_type, 
                $address, $city, $postal_code
            );

            if (!$insert_stmt->execute()) {
                throw new Exception("Insert failed: " . $insert_stmt->error);
            }
            $successMessage = "User added successfully!";
        }

        if (isset($_POST['UpdateBtn'])) {
            $update_stmt = $conn->prepare("UPDATE users SET
                first_name = ?, last_name = ?, id_number = ?, phone_number = ?,
                phase_count = ?, user_type = ?, property_type = ?,
                address = ?, city = ?, postal_code = ?
                WHERE id = ?");
            
            $update_stmt->bind_param("ssssssssssi", 
                $first_name, $last_name, $id_number, $phone_number, 
                $phase_count, $user_type, $property_type, 
                $address, $city, $postal_code, $id
            );

            if (!$update_stmt->execute()) {
                throw new Exception("Update failed: " . $update_stmt->error);
            }
            $successMessage = "User updated successfully!";
        }

    } catch (Exception $e) {
        $errors['database'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        tr:hover {
            background-color: #f3f4f6;
            cursor: pointer;
        }
    </style>
    <script>
        function fillUserForm(row) {
            const cells = row.getElementsByTagName('td');
            document.querySelector('[name="id"]').value = cells[0].innerText;
            document.querySelector('[name="first_name"]').value = cells[1].innerText;
            document.querySelector('[name="last_name"]').value = cells[2].innerText;
            document.querySelector('[name="id_number"]').value = cells[3].innerText;
            document.querySelector('[name="phone_number"]').value = cells[4].innerText;
            
            // Select dropdowns
            document.querySelector('[name="phase_count"]').value = cells[5].innerText;
            document.querySelector('[name="user_type"]').value = cells[6].innerText;
            document.querySelector('[name="property_type"]').value = cells[7].innerText;
            
            document.querySelector('[name="address"]').value = cells[8].innerText;
            document.querySelector('[name="city"]').value = cells[9].innerText;
            document.querySelector('[name="postal_code"]').value = cells[10].innerText;
        }
    </script>
</head>
<body>
    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 mt-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle">Admin Panel</p>
    </div>

    <?php include 'admin_navbar.php' ?>

    <div class="bg-gray-100 p-8 mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-6xl">
        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $successMessage ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php foreach ($errors as $error): ?>
                    <span class="block sm:inline"><?= $error ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        

        <div class="mt-8">
            <p class="text-xl font-bold mb-4">User List</p>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg" style="max-height: 400px;">
                <?php
                $sql_users = "SELECT * FROM users";
                if ($result_users = mysqli_query($conn, $sql_users)) {
                    if (mysqli_num_rows($result_users) > 0) {
                        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">
                            <thead class="text-xs uppercase bg-white text-black sticky top-0">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">First Name</th>
                                    <th class="px-6 py-3">Last Name</th>
                                    <th class="px-6 py-3">ID Number</th>
                                    <th class="px-6 py-3">Phone</th>
                                    <th class="px-6 py-3">Phase</th>
                                    <th class="px-6 py-3">Type</th>
                                    <th class="px-6 py-3">Property</th>
                                    <th class="px-6 py-3">Address</th>
                                    <th class="px-6 py-3">City</th>
                                    <th class="px-6 py-3">Postal</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>';

                        while ($row = mysqli_fetch_assoc($result_users)) {
                            echo "<tr onclick='fillUserForm(this)' class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                            echo "<td class='px-6 py-4'>".$row['id']."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['first_name'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['last_name'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['id_number'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['phone_number'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['phase_count'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['user_type'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['property_type'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['address'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['city'])."</td>";
                            echo "<td class='px-6 py-4'>".htmlspecialchars($row['postal_code'])."</td>";
                            echo "<td class='px-6 py-4'>
                                    <a href='delete_user.php?id=".$row['id']."' 
                                       onclick='return confirm(\"Are you sure you want to delete this user?\")'
                                       class='text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo '<div class="text-center py-4 text-gray-500">No users found.</div>';
                    }
                }
                ?>
            </div>
        </div>
        <form method="POST" action="">        
            <input type="hidden" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <div>
                    <label class="block text-black pb-2">First Name</label>
                    <input class="w-full p-2 border rounded" type="text" name="first_name" required>
                </div>
                <div>
                    <label class="block text-black pb-2">Last Name</label>
                    <input class="w-full p-2 border rounded" type="text" name="last_name" required>
                </div>
                <div>
                    <label class="block text-black pb-2">ID Number</label>
                    <input class="w-full p-2 border rounded" type="text" name="id_number" required>
                </div>
                <div>
                    <label class="block text-black pb-2">Phone Number</label>
                    <input class="w-full p-2 border rounded" type="text" name="phone_number" required>
                </div>
                <div>
                    <label class="block text-black pb-2">Phase Count</label>
                    <select class="w-full p-2 border rounded" name="phase_count" required>
                        <option value="Single Phase">Single Phase</option>
                        <option value="Three Phase">Three Phase</option>
                    </select>
                </div>
                <div>
                    <label class="block text-black pb-2">User Type</label>
                    <select class="w-full p-2 border rounded" name="user_type" required>
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Delivery_Personnel">Delivery Personnel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-black pb-2">Property Type</label>
                    <select class="w-full p-2 border rounded" name="property_type" required>
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-black pb-2">Postal Code</label>
                    <input class="w-full p-2 border rounded" type="text" name="postal_code">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-black pb-2">Address</label>
                <textarea class="w-full p-2 border rounded" name="address" rows="2"></textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-black pb-2">City</label>
                <input class="w-full p-2 border rounded" type="text" name="city">
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" name="InsertBtn"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Add New User
                </button>
                
                <button type="submit" name="UpdateBtn"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Update User
                </button>
            </div>
        </form>
    </div>

    <?php include 'footer.html'; ?>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
});
</script>      
</body>
</html>