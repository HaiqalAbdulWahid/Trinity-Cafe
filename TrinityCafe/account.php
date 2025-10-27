<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: customer.php");
    exit;
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

$orders = [];
$sql = "SELECT id, total_price, order_date 
        FROM orders 
        WHERE customer_email = (SELECT email FROM users WHERE id = ?)
        ORDER BY order_date DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Trinity Cafe</title>
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
                    <a href="account.php" class="text-lg text-yellow-600 font-bold border-b-2 border-yellow-600 pb-1">My Account</a>
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

    <main class="container mx-auto p-6 mt-8">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">
                Welcome, <?php echo htmlspecialchars($user_name); ?>
            </h1>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white text-lg font-semibold px-6 py-2 rounded-lg transition duration-300">
                Logout
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 w-full">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">Your Past Orders</h2>
            
            <?php if (empty($orders)): ?>
                <p class="text-lg text-gray-600">You have not placed any orders yet.</p>
            <?php else: ?>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2">
                            <th class="text-lg p-2">Order ID</th>
                            <th class="text-lg p-2">Date</th>
                            <th class="text-lg p-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-b">
                                <td class="p-2 text-gray-700">#<?php echo $order['id']; ?></td>
                                <td class="p-2 text-gray-700"><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></td>
                                <td class="p-2 text-gray-700">RM <?php echo number_format($order['total_price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white mt-12 py-6 shadow-inner">
        <div class="container mx-auto text-center text-gray-600">
            <p>&copy; 2025 Trinity Cafe. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>