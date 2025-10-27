<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['register_error'] = "Please fill in all fields.";
            header("Location: register.php");
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        try {
            $stmt->execute();
           
            header("Location: customer.html");
            exit;
            
        } catch (mysqli_sql_exception $e) {
           
            if ($e->getCode() == 1062) {
                $_SESSION['register_error'] = "This email address is already taken.";
            } else {
                $_SESSION['register_error'] = "An error occurred: " . $e->getMessage();
            }
            
            header("Location: register.php");
            exit;
        }

    } else {
        $_SESSION['register_error'] = "Invalid form submission.";
        header("Location: register.php");
        exit;
    }
    
} else {
    header("Location: register.php");
    exit;
}
?>