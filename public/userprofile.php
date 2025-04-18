<?php
require_once '../includes/session_manager.php';
$sessionManager = new SessionManager();
$sessionManager->checkAccess(); // Removed admin-only restriction

error_reporting(0);
ini_set('display_errors', 0);


require_once 'dB_Connection.php';

$errors = [];
$successMessage = "";
$userData = null;

// Fetch current user's data
try {
    $userId = $_SESSION['user_id'] ?? null; // Assuming you store user_id in session
    if ($userId) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
    }
} catch (Exception $e) {
    $errors['database'] = "Error fetching user data: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UpdateProfile'])) {
    try {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');

        if (empty($first_name)) throw new Exception("First name is required");
        if (empty($last_name)) throw new Exception("Last name is required");
        if (empty($phone_number)) throw new Exception("Phone number is required");

        $update_stmt = $conn->prepare("UPDATE users SET
            first_name = ?, last_name = ?, phone_number = ?,
            address = ?, city = ?, postal_code = ?
            WHERE id = ?");
        
        $update_stmt->bind_param("ssssssi", 
            $first_name, $last_name, $phone_number, 
            $address, $city, $postal_code, $userId
        );

        if (!$update_stmt->execute()) {
            throw new Exception("Update failed: " . $update_stmt->error);
        }
        $successMessage = "Profile updated successfully!";
        
        // Refresh user data after update
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
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
    <title>My Profile - NPH Solar Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include 'navbar.php' ?>
    <div class=" mt-7 ">
        

        <!-- Profile Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <?php if (!empty($successMessage)): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= $successMessage ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <span class="block sm:inline"><?= $error ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center space-x-5 mb-8">
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-2xl text-gray-600">
                                        <?= strtoupper(substr($userData['first_name'] ?? '', 0, 1)) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                <?= htmlspecialchars($userData['first_name'] ?? '') ?> <?= htmlspecialchars($userData['last_name'] ?? '') ?>
                            </h2>
                            <p class="text-sm text-gray-500">
                                <?= htmlspecialchars($userData['user_type'] ?? '') ?> Account
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" value="<?= htmlspecialchars($userData['first_name'] ?? '') ?>" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($userData['last_name'] ?? '') ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" value="<?= htmlspecialchars($userData['phone_number'] ?? '') ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Number</label>
                                <input type="text" value="<?= htmlspecialchars($userData['id_number'] ?? '') ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                                    disabled>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" value="<?= htmlspecialchars($userData['city'] ?? '') ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                                <input type="text" name="postal_code" value="<?= htmlspecialchars($userData['postal_code'] ?? '') ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700">Installation Type</label>
                                        <input type="text" value="<?= htmlspecialchars($userData['phase_count'] ?? '') ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                                            disabled>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700">Property Type</label>
                                        <input type="text" value="<?= htmlspecialchars($userData['property_type'] ?? '') ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" name="UpdateProfile"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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