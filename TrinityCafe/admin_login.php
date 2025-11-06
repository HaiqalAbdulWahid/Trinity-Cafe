<?php
session_start();

$error_message = null;
if (isset($_SESSION['admin_error'])) {
    $error_message = $_SESSION['admin_error'];
    unset($_SESSION['admin_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Trinity Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="bg-gray-800 text-gray-100">

    <main class="container mx-auto p-6 mt-20 flex justify-center">
        <div class="bg-white rounded-lg shadow-lg p-10 w-full max-w-md text-gray-800">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Admin Panel Login</h1>
            
            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <span><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>
            
            <form action="admin_logic.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-lg text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-lg text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                </div>
                <button type="submit" class="w-full bg-yellow-600 text-white text-lg font-semibold px-6 py-3 rounded-lg hover:bg-yellow-700 transition duration-300">
                    Login
                </button>
            </form>
        </div>
    </main>

</body>
</html>