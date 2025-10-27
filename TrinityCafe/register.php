<?php
session_start();

$error_message = null;
if (isset($_SESSION['register_error'])) {
    $error_message = $_SESSION['register_error'];
    unset($_SESSION['register_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Trinity Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="home.html" class="flex items-center space-x-2">
                
                <img src="Images/Trinity_Icon.png" 
     alt="Trinity Cafe Logo" 
     class="h-12 w-auto"
     onerror="this.src='https://placehold.co/150x50/F0E68C/8B4513?text=Trinity+Cafe'; this.onerror=null;">
                <span class="text-2xl font-bold text-yellow-700 hidden sm:block">Trinity Cafe</span>
            </a>
            <ul class="flex items-center space-x-6">
                <li>
                    <a href="featured.html" class="text-lg text-gray-700 hover:text-yellow-600 transition duration-300">Featured Menu</a>
                </li>
                <li>
                    <a href="customer.php" class="text-lg text-yellow-600 font-bold border-b-2 border-yellow-600 pb-1">Customer Account</a>
                </li>
                <li>
                    <a href="cart.php" class="flex items-center text-lg text-gray-700 hover:text-yellow-600 transition duration-300 bg-yellow-100 hover:bg-yellow-200 rounded-full px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cart
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto p-6 mt-8 flex justify-center">
        
        <div class="bg-white rounded-lg shadow-lg p-10 w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Create Account</h1>
            
            <?php
            if ($error_message):
            ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $error_message; ?></span>
                </div>
            <?php
            endif;
            ?>
            
            <form action="register_logic.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-lg text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-lg text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-lg text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                </div>
                <button type="submit" class="w-full bg-yellow-600 text-white text-lg font-semibold px-6 py-3 rounded-lg hover:bg-yellow-700 transition duration-300">
                    Sign Up
                </button>
                <p class="text-center text-gray-600 mt-4">
                    Already have an account? <a href="customer.php" class="text-yellow-700 hover:underline">Login</a>
                </p>
            </form>
        </div>

    </main>

    <footer class="bg-white mt-12 py-6 shadow-inner">
        <div class="container mx-auto text-center text-gray-600">
            <p>&copy; 2025 Trinity Cafe. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>