<?php
session_start(); // Start the session

// Include the database connection script
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $birthday = isset($_POST['birthdayDate']) ? $_POST['birthdayDate'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $phone = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
    $email = isset($_POST['emailAddress']) ? $_POST['emailAddress'] : '';
    $idNumber = isset($_POST['IdNumber']) ? $_POST['IdNumber'] : '';

    // Create a new User object
    $user = new User($firstName, $lastName, $birthday, $gender, $email, $phone, $idNumber);
    
   //Save the user to the session
    $_SESSION['User'] =$user;
    $_SESSION['email'] =$email;

    // Check if the user already exists
    if ($user->userExists($link)) {
        echo "Error: User with this email or ID number already exists.";
    } else {
        // Save the user to the database
        $result = $user->saveToDatabase($link);

        // Output the result
        echo $result;
    }

    

    // Close the database connection
    $link->close();
    //require_once '../password.html'
    header('Location: ../password.html');
} else {
    echo "Invalid request method.";
}
?>
