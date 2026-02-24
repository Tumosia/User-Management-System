<?php
session_start();
require_once(__DIR__ . '/../../src/controllers/AuthController.php');
require_once(__DIR__ . '/../../src/controllers/UserController.php');

$auth = new AuthController();
if(!$auth->isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$userCtrl = new UserController();
$id = $_GET['id'];

// Check if deleting multiple users
if(isset($_GET['many'])){
    $result = $userCtrl->deleteMultiple($id);
} else {
    $result = $userCtrl->deleteUser($id);
}

$_SESSION['msg'] = $result;
header('Location: dashboard.php');
exit;
?>
