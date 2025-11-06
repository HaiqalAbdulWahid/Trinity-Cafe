<?php
session_start(); // Start the session so we can access it

// Check if the admin is logged in and unset the *specific*
// session variable we created for them.
// We do this so we don't accidentally log out a customer
// who might be on the same computer.
if (isset($_SESSION['admin_logged_in'])) {
    unset($_SESSION['admin_logged_in']);
}

// You could also use session_destroy() if you want to
// log out *everyone* (admin and customer), but unset() is safer.

// Send the admin back to the admin login page
header("Location: admin_login.php");
exit;
?>