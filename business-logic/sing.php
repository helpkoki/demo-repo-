<?php
session_start(); // Start the session

// Include the database connection script
require_once '../database-config/';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $birthday = $_POST['birthdayDate'];
    $gender = $_POST['gender'];
    $email = $_POST['emailAddress'];
    $phone = $_POST['phoneNumber'];
    $idNumber = $_POST['idNumber'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO UserDetails (firstName, lastName, birthday, gender, emailAddress, phoneNumber, idNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstName, $lastName, $birthday, $gender, $email, $phone, $idNumber);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
