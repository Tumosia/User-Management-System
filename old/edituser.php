<?php
session_start();
require('connect.php');

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
}
echo($id);

    try {

    $sql = "UPDATE users SET name = '$name', email= '$email' WHERE id= '$id' ;";

    $user = $conn->query($sql);


    if($user === true){
        $_SESSION['msg'] = array(
            'success' => true,
            'message' => 'User updated successfully'
        );
    } else{
        $_SESSION['msg'] = array(
            'success' => false,
            'message' => 'Error updating user!'
        );
    }
}catch(Exception $ex){

        $_SESSION['msg'] = array(
            'success' => true,
            'message' => 'Error updating user!'
        );

        file_put_contents('editError.txt',ex);
    }


header('Location:all.php');
?>