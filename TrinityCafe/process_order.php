<?php
session_start();
include 'db_connect.php';

if (empty($_SESSION['cart']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: cart.php");
    exit;
}

$customer_name = htmlspecialchars($_POST['full_name']);
$customer_email = htmlspecialchars($_POST['email']);
$customer_address = htmlspecialchars($_POST['address']);

$cart_items_details = [];
$total_price = 0;
$product_ids = array_keys($_SESSION['cart']);
$safe_ids = array_map('intval', $product_ids);
$id_string = implode(',', $safe_ids);

$sql = "SELECT * FROM products WHERE id IN ($id_string)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $quantity = $_SESSION['cart'][$product_id];
        $subtotal = $row['price'] * $quantity;
        $total_price += $subtotal;
        
        $cart_items_details[] = [
            'id' => $product_id,
            'quantity' => $quantity,
            'price' => $row['price'] 
        ];
    }
} else {
    
    header("Location: cart.php");
    exit;
}

$conn->begin_transaction(); 

try {
    $sql_order = "INSERT INTO orders (customer_name, customer_email, customer_address, total_price) 
                  VALUES (?, ?, ?, ?)";
                  
    $stmt = $conn->prepare($sql_order);
   
    $stmt->bind_param("sssd", $customer_name, $customer_email, $customer_address, $total_price);
    $stmt->execute();
    
    $order_id = $conn->insert_id;
    
    $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                  VALUES (?, ?, ?, ?)";
    $stmt_items = $conn->prepare($sql_items);
    
    foreach ($cart_items_details as $item) {
        
        $stmt_items->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt_items->execute();
    }
    
    $conn->commit();

    $_SESSION['cart'] = array();
 
    header("Location: complete.php?order=success");
    exit;

} catch (mysqli_sql_exception $exception) {
    
    $conn->rollback(); 
    
    die("Error saving order. Please try again. " . $exception->getMessage());
}

?>