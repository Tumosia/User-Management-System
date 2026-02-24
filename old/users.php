<?php
session_start();
require('connect.php');

    if(isset($_POST['name'])) {
        $name =  addslashes( $_POST['name']);
        $email = $_POST['email'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    }

    try {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    $results = $conn->query($sql);

    if($results === TRUE){
       
        $_SESSION['msg'] = array(
            'success' => true,
            'message' => 'saved'
        );       
    } else{
        $_SESSION['msg'] = array(
            'success' => false,
            'message' => 'error'
        );
    }
    } catch (Exception $th) {
        $_SESSION['msg'] = array(
            'success' => false,
            'message' => 'error'
        );
        file_put_contents('error.txt',$th);
    }
   

    header('location:all.php');
