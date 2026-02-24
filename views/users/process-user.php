<?php
session_start();
require_once(__DIR__ . '/../../src/controllers/AuthController.php');
require_once(__DIR__ . '/../../src/controllers/UserController.php');

header('Content-Type: application/json');

$auth = new AuthController();
if(!$auth->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$mode = $_POST['mode'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$userId = $_POST['user_id'] ?? '';

if(empty($mode) || empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$userCtrl = new UserController();

if($mode === 'add') {
    $result = $userCtrl->addUser($name, $email);
} elseif($mode === 'edit') {
    if(empty($userId)) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        exit;
    }
    $result = $userCtrl->updateUser($userId, $name, $email);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid mode']);
    exit;
}

echo json_encode($result);
?>
