<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order_id'])) {
    $delete_id = intval($_POST['delete_order_id']);
    
    $stmt_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt_items->bind_param("i", $delete_id);
    $stmt_items->execute();
    
    $stmt_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_order->bind_param("i", $delete_id);
    $stmt_order->execute();
    
    header("Location: admin_dashboard.php");
    exit;
}

$orders = [];
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
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
    <title>Admin Dashboard - Trinity Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cormorant Garamond', serif; }
        .table-cell { padding: 12px 16px; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="images/Trinity_Icon.png" alt="Trinity Cafe Logo" class="h-12 w-auto">
                <h1 class="text-2xl font-bold text-yellow-700">Trinity Cafe Admin Panel</h1>
            </div>
            <a href="admin_logout.php" class="bg-red-500 hover:bg-red-600 text-white text-lg font-semibold px-6 py-2 rounded-lg transition duration-300">
                Logout
            </a>
        </nav>
    </header>

    <main class="container mx-auto p-6 mt-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">All Customer Orders</h2>
        
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <?php if (empty($orders)): ?>
                <p class="p-8 text-xl text-gray-600">No orders have been placed yet.</p>
            <?php else: ?>
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="table-cell text-lg font-semibold text-gray-600">Order ID</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Date</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Customer Name</th>
                            <th class="table-cell text-lg font-semibold text-gray-600">Total</th>
                            <th class="table-cell text-lg font-semibold text-gray-600 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell text-lg text-gray-800 font-medium">#<?php echo $order['id']; ?></td>
                                <td class="table-cell text-gray-700"><?php echo date('M j, Y, g:i a', strtotime($order['order_date'])); ?></td>
                                <td class="table-cell text-gray-700">
                                    <div class="font-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                                </td>
                                <td class="table-cell text-lg text-gray-800 font-medium">RM <?php echo number_format($order['total_price'], 2); ?></td>
                                <td class="table-cell text-center">
                                    <form action="admin_dashboard.php" method="POST" onsubmit="return confirm('Are you sure you want to delete Order #<?php echo $order['id']; ?>?');">
                                        <input type="hidden" name="delete_order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-semibold transition duration-300">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
