<?php
session_start();
require('connect.php');

try {

$id = $_GET['id'];


$sql = "DELETE FROM users WHERE id='$id' ";
if(isset($_GET['many'])){
$sql = "DELETE FROM users WHERE id IN ($id); ";

}
$results = $conn->query($sql);

if($results === TRUE){
        $_SESSION['msg'] = array(
            'success' => true,
            'message' => 'User deleted successfully'
        );
    } else{
        $_SESSION['msg'] = array(
            'success' => false,
            'message' => 'Error deleting user!'
        );
    }
}catch (Exception $ex){
    $_SESSION['msg'] = array(
        'success' => false,
        'message' => 'Server Error!'
    );
}
header('Location:all.php')
?>