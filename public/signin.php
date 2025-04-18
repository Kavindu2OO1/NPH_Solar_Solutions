<?php
require_once '../config/database.php';
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();

// Check if the user is logged in, if  yes redirect to home page
if (isset($_SESSION['loggedin']) ) {
    header("Location: Home_Page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
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
    //database connection
require_once 'dB_Connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $nic = trim($_POST['nic'] ?? '');
    $password = $_POST['password'] ?? '';

    // Store old input
    $old_input = [
        'nic' => htmlspecialchars($nic),
    ];

    // Validation
    
    if (empty($nic)) {
        $errors['nic'] = "ID Number is required";
    } elseif (!preg_match('/^(\d{10,12}|\d{9}[A-Z])$/', $nic)) {
        $errors['nic'] = "Invalid ID Number format (10-12 digits or 9 digits followed by an uppercase letter)";
    }



    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    }


    if (empty($errors)) {
        // Prepare SQL query
        $stmt = $conn->prepare("SELECT id, first_name, password_hash, user_type FROM users WHERE id_number = ?");
        $stmt->bind_param("s", $nic);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if(password_verify($password, $user['password_hash'])) {
    
               $sessionManager->login([
                'id' => $user['id'],
                'user_type' => $user['user_type'],
                'first_name' => $user['first_name']
            ]);

            
               switch($user['user_type']) {
                case 'Admin':
                    header("Location: adminpanel.php");
                    break;
                case 'Manager':
                    header("Location: adminpanel.php");
                    break;
                case 'Delivery_Personnel':
                    header("Location: vieworders.php");
                    break;
                case 'User':
                    header("Location: Home_Page.php");
                    break;
            }
            exit();

                

            } else {
                $errors['login'] = "*Invalid ID Number or Password";
            }
        } else {
            $errors['login'] = "*Invalid ID Number or Password";
        }


        
        $stmt->close();
    }
    $conn->close();
}
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
            <input class="placeholder:text-slate-400 block bg-white w-80 border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="ID Number" type="text" name="nic" value="<?= htmlspecialchars($old_input['nic'] ?? '') ?>"/>
            <?php if (isset($errors['nic'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= $errors['nic'] ?></p>
            <?php endif; ?>
            <p class="font-sans text-black pb-2 text-left">Password</p>
            <input class="placeholder:text-slate-400 block bg-white w-80 border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Password" type="password" name="password"/>
            <?php if (isset($errors['password'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= $errors['password'] ?></p>
            <?php endif; ?>
            
            <div class="mt-6">
                <button
                    type="submit"
                    name="calculateBtn"
                    class="focus:outline-none hover:text-black text-white bg-orange-600 hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 font-medium rounded-lg text-sm px-3 py-2 align-middle w-80">
                    Sign In
                </button>
            </div>
            <?php if (isset($errors['login'])): ?>
                <div class="error text-red-600 font-semibold text-sm mt-2"><?= $errors['login'] ?></div>
            <?php endif; ?>
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

       

    </div>
    

    

    <?php 
            include 'footer.html';
     ?>


    
</body>
</html>
