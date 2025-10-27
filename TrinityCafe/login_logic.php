<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['email']) && isset($_POST['password'])) {
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = "Please fill in all fields.";
            header("Location: customer.php");
            exit;
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                header("Location: account.php");
                exit;
            } else {
                $_SESSION['login_error'] = "Incorrect email or password.";
                header("Location: customer.php");
                exit;
            }
        } else {
            $_SESSION['login_error'] = "Incorrect email or password.";
            header("Location: customer.php");
            exit;
        }

    } else {
        $_SESSION['login_error'] = "Invalid form submission.";
        header("Location: customer.php");
        exit;
    }

} else {
    header("Location: customer.php");
    exit;
}
?>