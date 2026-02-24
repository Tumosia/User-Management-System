<?php

session_start();

// Check if user is logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Redirect to dashboard
    header('Location: ../views/users/dashboard.php');
    exit;
} else {
    // Redirect to login
    header('Location: ../views/auth/login.php');
    exit;
}
?>
