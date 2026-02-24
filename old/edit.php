<?php
session_start();
require_once(__DIR__ . '/../../src/controllers/AuthController.php');
require_once(__DIR__ . '/../../src/controllers/UserController.php');

$auth = new AuthController();
if(!$auth->isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit;
}

$user = '';
$userCtrl = new UserController();

if(isset($_GET['id'])){
    $id = $_REQUEST['id'];
    $_SESSION['id'] = $id;
    $user = $userCtrl->getUserById($id);
}

if(isset($_POST['submit'])){
    $id = $_SESSION['id'];
    $result = $userCtrl->updateUser($id, $_POST['name'], $_POST['email']);
    
    $_SESSION['msg'] = $result;
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
<div class="formbg-outer py-5">
    <div class="formbg">
    <div class="formbg-inner padding-horizontal--48">
     <div class="field padding-bottom--24" style="display: flex; align-items: center; gap: 80px;">
            <img src="../../public/img/back.png" alt="Back" onclick="window.location.href='dashboard.php'" style="cursor: pointer; width: 30px; height: 30px;">
            <h4><span>Edit User</span></h4>
        </div>
    <form method="POST" action="edit.php">
    <div class="field padding-bottom--24">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?= $user['name'] ?? ''; ?>">
    </div>
    <div class="field padding-bottom--24">
        <label for="email">Email</label>
        <input type="email" name="email" value="<?= $user['email'] ?? ''; ?>">
    </div>
    <div class="field padding-bottom--24">
        <input type="submit" name="submit" value="Update">
    </div>
    </form>
    </div>
    </div>
    </div>
</body>
</html>
