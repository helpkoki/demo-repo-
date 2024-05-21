<?php
session_start(); // Start the session

// Include the database connection script
require_once '../database-config/config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $birthday = isset($_POST['birthdayDate']) ? $_POST['birthdayDate'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : ''; // New address input
    $phone = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : 'other';
    $email = isset($_POST['emailAddress']) ? $_POST['emailAddress'] : '';
    $idNumber = isset($_POST['IdNumber']) ? $_POST['IdNumber'] : '';

    // Prepare and bind
    $stmt = mysqli_prepare($link, "INSERT INTO UserDetails (firstName, lastName, birthday, gender, emailAddress, phoneNumber, idNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssss", $firstName, $lastName, $birthday, $gender, $email, $phone, $idNumber);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request method.";
}

// Close the connection
mysqli_close($link);
?>
