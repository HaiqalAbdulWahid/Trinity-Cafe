<?php
session_start();
include 'db_connect.php'; // We need this to talk to the database

// --- PAGE PROTECTION ---
// If the admin is NOT logged in (if the session variable
// we set in admin_logic.php doesn't exist),
// kick them back to the login page.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// --- GET ALL ORDERS ---
// If we are logged in, get the data.
// We will get all orders, starting with the newest ones first.
$orders = [];
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);

// Check if the query ran successfully and returned rows
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row; // Add each order row to our $orders array
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Trinity Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cormorant Garamond', serif; }
        /* A custom class for our table cells to give them more padding */
        .table-cell { padding: 12px 16px; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-yellow-700">Trinity Cafe Admin Panel</h1>
            <!-- This button will link to the logout file we will make next -->
            <a href="admin_logout.php" class="bg-red-500 hover:bg-red-600 text-white text-lg font-semibold px-6 py-2 rounded-lg transition duration-300">
                Logout
            </a>
        </nav>
    </header>

    <main class="container mx-auto p-6 mt-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">All Customer Orders</h2>
        
        <!-- This container makes the table scroll horizontally on small screens -->
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            
            <?php if (empty($orders)): ?>
                <!-- Show this if there are no orders in the database -->
                <p class="p-8 text-xl text-gray-600">No orders have been placed yet.</p>
            <?php else: ?>
                <!-- Otherwise, show the table -->
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="table-cell text-lg font-semibold text-gray-600">Order ID</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Date</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Customer Name</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Email</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Address</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- We now loop through each order we fetched from the database -->
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell text-lg text-gray-800 font-medium">#<?php echo $order['id']; ?></td>
                                <td class="table-cell text-gray-700"><?php echo date('M j, Y, g:i a', strtotime($order['order_date'])); ?></td>
                                <td class="table-cell text-gray-700"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td class="table-cell text-gray-700"><?php echo htmlspecialchars($order['customer_email']); ?></td>
                                <td class="table-cell text-gray-700"><?php echo htmlspecialchars($order['customer_address']); ?></td>
                                <td class="table-cell text-lg text-gray-800 font-medium">RM <?php echo number_format($order['total_price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>