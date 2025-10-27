<?php
session_start();
include 'db_connect.php';

$cart_products = [];
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
                $cart_products[] = $row;
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
    <title>Your Cart - Trinity Cafe</title>
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
                    <a href="cart.php" class="flex items-center text-lg text-yellow-800 font-bold bg-yellow-200 rounded-full px-4 py-2">
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
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Your Shopping Cart</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-4xl mx-auto">
            
            <?php if (empty($cart_products)): ?>
                
                <div class="border-b pb-4 mb-4">
                    <p class="text-xl text-gray-700">Your cart is currently empty.</p>
                    <p class="text-lg text-gray-600 mt-2">
                        Visit our <a href="featured.html" class="text-yellow-700 hover:underline">Featured Menu</a> to add items.
                    </p>
                </div>

            <?php else: ?>
                
                <?php foreach ($cart_products as $product): ?>
                    <?php
                        $product_id = $product['id'];
                        $quantity = $_SESSION['cart'][$product_id];
                        $subtotal = $product['price'] * $quantity;
                        $total_price += $subtotal;
                    ?>
                    <div class="flex flex-col md:flex-row items-center justify-between py-6 border-b">
                        <div class="flex items-center space-x-4 mb-4 md:mb-0">
                            <!-- 
                              This image is loaded from the database
                            -->
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="rounded-lg w-24 h-24 object-cover"
                                 onerror="this.src='https://placehold.co/100x100/A1887F/FFFFFF?text=Item'; this.onerror=null;">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($product['name']); ?></h2>
                                <p class="text-lg text-gray-600">RM <?php echo number_format($product['price'], 2); ?> (each)</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <p class="text-xl">Qty: <?php echo $quantity; ?></p>
                            <p class="text-2xl font-semibold text-gray-800">RM <?php echo number_format($subtotal, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
            
            <div class="mt-8 flex flex-col md:flex-row justify-end items-center">
                <div class="text-3xl font-bold mb-4 md:mb-0 md:mr-8">
                    <span>Total:</span>
                    <span class="text-yellow-800">RM <?php echo number_format($total_price, 2); ?></span>
                </div>
                <a href="confirm.php" 
                   class="block w-full md:w-auto text-center bg-yellow-600 text-white text-lg font-semibold px-10 py-3 rounded-lg hover:bg-yellow-700">
                    Proceed to Checkout
                </a>
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