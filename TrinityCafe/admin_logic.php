<?php
session_start();

// --- SET YOUR ADMIN LOGIN DETAILS HERE ---
// You can change "admin" and "password123" to anything you want
$ADMIN_USERNAME = "admin";
$ADMIN_PASSWORD = "password123";
// ------------------------------------

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if both fields were filled out
    if (isset($_POST['username']) && isset($_POST['password'])) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the submitted data matches our hardcoded admin details
        if ($username === $ADMIN_USERNAME && $password === $ADMIN_PASSWORD) {
            
            // --- SUCCESS ---
            // Login is correct.
            // We set a session variable to remember the admin is logged in.
            $_SESSION['admin_logged_in'] = true;
            unset($_SESSION['admin_error']); // Clear any old errors
            
            // Send the admin to the dashboard
            header("Location: admin_dashboard.php");
            exit;

        } else {
            // --- FAILURE ---
            // Login is incorrect.
            // Set an error message and send the admin back to the login page.
            $_SESSION['admin_error'] = "Incorrect username or password.";
            header("Location: admin_login.php");
            exit;
        }

    } else {
        // One of the fields was empty
        $_SESSION['admin_error'] = "Please fill in all fields.";
        header("Location: admin_login.php");
        exit;
    }

} else {
    // If someone tries to go to this file directly without posting a form,
    // just send them back to the login page.
    header("Location: admin_login.php");
    exit;
}
?>