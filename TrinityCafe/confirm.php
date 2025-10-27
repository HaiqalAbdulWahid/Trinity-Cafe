<?php
session_start();
include 'db_connect.php';

$cart_items_details = [];
$total_price = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    
    $product_ids = array_keys($_SESSION['cart']);
    $safe_ids = array_map('intval', $product_ids);
    
    if (!empty($safe_ids)) {
        $id_string = implode(',', $safe_ids);
        
        $sql = "SELECT * FROM products WHERE id IN ($id_string)";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = $row['id'];
                $quantity = $_SESSION['cart'][$product_id];
                $subtotal = $row['price'] * $quantity;
                $total_price += $subtotal;
                
                $row['quantity'] = $quantity;
                $row['subtotal'] = $subtotal;
                $cart_items_details[] = $row;
            }
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trinity Cafe - Confirm Order</title>
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
                    <a href="customer.php" class="text-lg text-gray-700 hover:text-yellow-600 transition duration-300">Customer Account</a>
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
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Confirm Your Order</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
            <div class="md:col-span-2">
               
                <form action="process_order.php" method="POST" class="bg-white rounded-lg shadow-lg p-8">
                    
                    <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-4">Customer Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-lg text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="full_name" name="full_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                        </div>
                        <div>
                            <label for="email" class="block text-lg text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="address" class="block text-lg text-gray-700 mb-2">Full Address</label>
                        <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                    </div>
                    
                    <h2 class="text-3xl font-semibold text-gray-800 mt-10 mb-6 border-b pb-4">Payment Information</h2>
                    
                    <div>
                        <label for="card_number" class="block text-lg text-gray-700 mb-2">Card Number</label>
                        <input type="text" id="card_number" name="card_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="expiry" class="block text-lg text-gray-700 mb-2">Expiry (MM/YY)</label>
                            <input type="text" id="expiry" name="expiry" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="MM/YY" required>
                        </div>
                        <div>
                            <label for="cvc" class="block text-lg text-gray-700 mb-2">CVC</label>
                            <input type="text" id="cvc" name="cvc" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="&bull;&bull;&bull;" required>
                        </div>
                    </div>
					
                    <button type="submit" class="mt-10 w-full text-center bg-yellow-600 text-white text-xl font-semibold px-10 py-4 rounded-lg hover:bg-yellow-700">
                        Confirm &amp; Pay
                    </button>
                    
                </form>
            </div>
            
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-8 sticky top-28">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-4">Order Summary</h2>
                    
                    <?php if (empty($cart_items_details)): ?>
                        <p class="text-gray-600 text-lg text-center py-4">
                            Your cart is empty.
                        </p>
                    <?php else: ?>
        
                        <div class="space-y-4">
                        <?php foreach ($cart_items_details as $item): ?>
                            <div class="flex justify-between items-center text-lg">
                                <div>
                                    <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($item['name']); ?></span>
                                    <span class="text-gray-600"> x <?php echo $item['quantity']; ?></span>
                                </div>
                                <span class="font-semibold text-gray-800">RM <?php echo number_format($item['subtotal'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                  
                    <div class="mt-6 border-t pt-6">
                        <div class="flex justify-between text-2xl font-bold text-yellow-800 border-t pt-4 mt-4">
                            <span>Total</span>
                            <span>RM <?php echo number_format($total_price, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

    <footer class="bg-white mt-12 py-6 shadow-inner">
        <div class="container mx-auto text-center text-gray-600">
            <p>&copy; 2025 Trinity Cafe. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>