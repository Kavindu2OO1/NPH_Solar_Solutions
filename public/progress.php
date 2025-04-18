<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
// Restrict access to only Admin role


//session_start();
error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 
// Set default timezone (adjust to your location)
date_default_timezone_set('Asia/Colombo');

// Initialize greeting
$greeting = "";
$currentHour = date('G');

// Check if user is logged in using SessionManager
if ($sessionManager->isLoggedIn()) {
  if ($currentHour >= 5 && $currentHour < 12) {
      $greeting = "Good morning";
  } elseif ($currentHour >= 12 && $currentHour < 18) {
      $greeting = "Good afternoon";
  } elseif ($currentHour >= 18 && $currentHour < 22) {
      $greeting = "Good evening";
  } else {
      $greeting = "Good day!";
  }
}
?>



<!-- get user Project from db -->
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', value: 1);


require_once 'dB_Connection.php';


// Handle form submission
$errors = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize inputs
        $id_number = trim($_POST['id_number'] ?? '');



        // Input validation
        if (empty($id_number)) throw new Exception("National ID is required");


        // Get user ID
        $stmt = $conn->prepare("SELECT id FROM appointments WHERE id = ?");
        $stmt->bind_param("s", $id_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->num_rows) throw new Exception("User not found with provided ID");
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Map values to database-friendly formats
        $status_value = $status_mapping[$status] ?? 'Pending';


        if (isset($_POST['UpdateBtn'])) {
            $update_stmt = $conn->prepare("UPDATE appointments 
                                         SET  status = ?
                                         WHERE id = ?");
            $update_stmt->bind_param("si", 
                $status_value, 
                $user_id, 

            );

            if (!$update_stmt->execute()) {
                throw new Exception("Update failed: " . $update_stmt->error);
            }
            $successMessage = "Appoinment updated successfully!";
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

   

      

<?php
$userId = (int)$sessionManager->getUserId();

if ($userId !== 0): ?>
<div class="bg-gray-100 p-8  mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl"> 
    <form method="POST" action="">      
                        <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>  
            <div class="relative overflow-x-auto mb-8">

            <?php if ($sessionManager->isLoggedIn()): ?>
                <div class="pl-2 rounded-md px-3 py-2 text-sm font-medium text-gray-700">
                    <?php 
               
                    echo '<span class="font-bold text-lg">' . htmlspecialchars($_SESSION['first_name']) . '</span>\'s project progress.'; 
                    ?>
                </div>
            <?php endif; ?>

            <?php
                require_once 'dB_Connection.php';

                
                
                echo '<div class="relative overflow-x-auto mb-8 " style="max-height: 300px; overflow-y: auto;" >';

                $sql_projects = "SELECT * FROM projects WHERE user_id = '$userId'";
                if ($result_projects = mysqli_query($conn, $sql_projects)) {
                    if (mysqli_num_rows($result_projects) > 0) {
                        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">';
                        echo '<thead class="text-xs uppercase bg-white text-black sticky top-0">';
                        echo "<tr>";
                        echo '<th scope="col" class="px-6 py-3">User Id</th>';
                        echo '<th scope="col" class="px-6 py-3">Project Name</th>';
                        echo '<th scope="col" class="px-6 py-3">Phase Count</th>';
                        echo '<th scope="col" class="px-6 py-3">Status</th>';
                        echo '<th scope="col" class="px-6 py-3">Price</th>';
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        while ($row = mysqli_fetch_array($result_projects)) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                            echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . 
                                htmlspecialchars($row['user_id']) . '</th>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['project_name']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['phase_count']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['status']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($row['price']) . '</td>';
                          
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        mysqli_free_result($result_projects);
                    } else {
                        echo '<div class="text-center py-4 text-gray-500">No projects found.</div>';
                    }
                }
                echo '</div>';

                

                // Close connection to db
                mysqli_close($conn);
                ?>
            </div>
<?php else: ?>
    <div class="bg-gray-100 p-8 mb-48 mt-48 drop-shadow-2xl rounded max-w-md mx-auto md:max-w-5xl">
    <form method="POST" action="">
        <p class="font-sans text-red-600 pb-2">* Check your project progress</p>
        <p class="font-sans text-black pb-2">Enter Your ID Number</p>
        <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="ID Number" type="text" name="ID_Number" required />

        <div class="mt-6">
            <button type="submit" name="calculateBtn" class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle">
                Check Progress
            </button>
        </div>
    </form>

    <?php
    require_once 'dB_Connection.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculateBtn'])) {
        $idNumber = $_POST['ID_Number']; 

       
        $sqlUser = "SELECT id, first_name, last_name FROM users WHERE id_number = ?";
        if ($stmtUser = mysqli_prepare($conn, $sqlUser)) {
            mysqli_stmt_bind_param($stmtUser, "s", $idNumber); 
            mysqli_stmt_execute($stmtUser);
            $resultUser = mysqli_stmt_get_result($stmtUser);

            if ($rowUser = mysqli_fetch_assoc($resultUser)) {
                $userId = $rowUser['id']; // Retrieve the user ID
                $firstName = $rowUser['first_name'];
                $lastName = $rowUser['last_name'];

                // get projects belonging to the user ID
                $sqlProjects = "SELECT * FROM projects WHERE user_id = ?";
                if ($stmtProjects = mysqli_prepare($conn, $sqlProjects)) {
                    mysqli_stmt_bind_param($stmtProjects, "i", $userId); 
                    mysqli_stmt_execute($stmtProjects);
                    $resultProjects = mysqli_stmt_get_result($stmtProjects);

                    if (mysqli_num_rows($resultProjects) > 0) {
                        // Display user details
                        echo '<div class="custom1 mt-6 grid grid-cols-1 gap-4">';
                        echo '<div class="shadow-lg rounded bg-white p-4">';
                        echo '<p class="font-sans text-black pb-2">Name: ' . htmlspecialchars($firstName . ' ' . $lastName) . '</p>';

                        // Display projects
                        echo '<p class="font-sans text-black pb-2">Projects:</p>';
                        echo '<div class="relative overflow-x-auto mb-8" style="max-height: 300px; overflow-y: auto;">';
                        echo '<table class="w-full text-sm text-left rtl:text-right text-black dark:text-white">';
                        echo '<thead class="text-xs uppercase bg-white text-black sticky top-0">';
                        echo "<tr>";
                        echo '<th scope="col" class="px-6 py-3">Project Name</th>';
                        echo '<th scope="col" class="px-6 py-3">Phase Count</th>';
                        echo '<th scope="col" class="px-6 py-3">Status</th>';
                        echo '<th scope="col" class="px-6 py-3">Price</th>';
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($rowProject = mysqli_fetch_assoc($resultProjects)) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($rowProject['project_name']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($rowProject['phase_count']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($rowProject['status']) . '</td>';
                            echo '<td class="px-6 py-4">' . htmlspecialchars($rowProject['price']) . '</td>';
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                        echo '</div>'; 
                        echo '</div>'; 
                        echo '</div>'; 
                    } else {
                        echo '<div class="text-center py-4 text-gray-500">No projects found for this user.</div>';
                    }

                    mysqli_stmt_close($stmtProjects);
                } else {
                    echo '<div class="text-center py-4 text-red-500">Error fetching projects.</div>';
                }
            } else {
                echo '<div class="text-center py-4 text-red-500">User not found.</div>';
            }

            mysqli_stmt_close($stmtUser);
        } else {
            echo '<div class="text-center py-4 text-red-500">Error fetching user details.</div>';
        }
    }
    ?>
</div>
<?php endif; ?>
    </div>

   
    <?php 
        include 'footer.html';
    ?>
   



    
</body>
</html>
