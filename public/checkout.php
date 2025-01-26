<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - NPH Solar Solutions</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('pictures/checkout-background.jpg');
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
        <p class="font-sans text-orange-500 text-5xl align-middle pb-8">Checkout</p>

        <div class="pb-8">
            <a
                href="http://127.0.0.1/xampp/NPH_Solar_Solutions/public/Home_Page.php"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Return To Home 
            </a>
        </div>
    </div>

    

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold mb-4">Billing Information</h2>
                <form action="confirmation.php" method="POST">
                    <div class="mb-4">
                        <label for="full-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="Full name" type="text" name="ID_Number"/>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="kavindu@gmail.com" type="text" name="ID_Number"/>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="521 road city" type="text" name="ID_Number"/>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="+94712603723" type="text" name="ID_Number"/>
                    </div>

                    <h2 class="text-2xl font-bold mt-8 mb-4">Payment Information</h2>
                    <div class="mb-4">
                        <label for="card-number" class="block text-sm font-medium text-gray-700">Card Number</label>
                        <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="xxxx xxxx xxxx xxxx" type="text" name="ID_Number"/>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="expiry-date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="MM/DD" type="text" name="ID_Number"/>
                        </div>

                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                            <input class="placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-2 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-black focus:ring-1 sm:text-sm" placeholder="123" type="text" name="ID_Number"/>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-green-300">Complete Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
