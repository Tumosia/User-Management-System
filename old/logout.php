<?php
session_start();

// Destroy all session data
session_destroy();

// Redirect to login
header('location: login.php');
exit;
?>
