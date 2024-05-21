<?php
session_start();
// Include the database connection script
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class
require_once '../Model/myTools.php'; // Include the User class
require_once '../Model/User_verify.php'; // Include the User class


$authenticator = new UserAuthenticator($link);

$email = $_POST['email'];
$password = $_POST['password'];

$user = $authenticator->verifyCredentials($email, $password);
if($user){
    
    $authenticator->initUserSession($user);
   
}else{
    $error_message ="User not found. Please Click the sign up link below.";
    $_SESSION["error_message"]=$error_message;
    
    header("Location: ../index.html");
}

?>