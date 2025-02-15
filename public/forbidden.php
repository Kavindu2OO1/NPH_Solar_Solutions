<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Access Forbidden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-white min-h-screen flex flex-col items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-xl p-8 max-w-md w-full space-y-6 transform transition-all duration-300 hover:shadow-2xl">
        <div class="text-center space-y-4">
            <div class="animate-pulse inline-flex items-center justify-center bg-orange-100 rounded-full w-20 h-20 mx-auto">
                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="space-y-2">
                <h1 class="text-4xl font-black text-green-600 animate-bounce">403</h1>
                <h2 class="text-2xl font-bold text-gray-800">Access Restricted</h2>
                <p class="text-gray-600 text-sm leading-relaxed">Oops! It seems you've ventured into restricted territory. Let's get you back home safely.</p>
            </div>
        </div>
        
        <div class="space-y-3">
            <a href="Home_Page.php" class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-95 shadow-md hover:shadow-lg">
                🏠 Return to Home
            </a>
            <p class="text-center text-sm text-gray-500">
                Need help? <a href="mailto:support@example.com" class="text-green-600 hover:text-green-700 font-medium">Contact support</a>
            </p>
        </div>
    </div>
</body>
</html>