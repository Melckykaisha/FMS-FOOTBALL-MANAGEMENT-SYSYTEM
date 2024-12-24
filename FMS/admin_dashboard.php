<?php
// Start the session to keep track of logged-in status
session_start();

// Define the admin password
$adminPassword = "0000";

// Check if the form was submitted and the password is provided
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredPassword = $_POST['password'];

    // Validate the entered password
    if ($enteredPassword === $adminPassword) {
        // Password is correct, set a session variable to indicate successful login
        $_SESSION['admin_logged_in'] = true;

        // Redirect to the admin page (admins.html)
        header("Location: admins.html");
        exit();
    } else {
        // Password is incorrect, redirect back to login page with an error
        header("Location: admin_login.html?error=Incorrect Password");
        exit();
    }
} else {
    // If accessed without form submission, redirect to login page
    header("Location: admin_login.html");
    exit();
}
?>