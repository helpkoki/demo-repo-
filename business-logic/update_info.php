<?php
session_start();
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class
require_once '../Model/myTools.php'; // Include the tools class
require_once '../Model/User_verify.php'; // Include the user verification class

$email = $_SESSION['email'];

// Retrieve POST variables, allowing for null values if they are not set
$firstName = $_POST['firstName'] ?? null;
$lastName = $_POST['lastName'] ?? null;
$email_post = $_POST['email'] ?? null;
$IdNumber = $_POST['IdNumber'] ?? null;
$phoneNumber = $_POST['phoneNumber'] ?? null;


// Build the SQL statement dynamically based on provided input
$fieldsToUpdate = [];
$types = '';
$values = [];

if ($lastName !== null && $lastName !== '') {
    $fieldsToUpdate[] = "lastName=?";
    $types .= 's';
    $values[] = $lastName;
}
if ($firstName !== null && $firstName !== '') {
    $fieldsToUpdate[] = "firstName=?";
    $types .= 's';
    $values[] = $firstName;
}
if ($IdNumber !== null && $IdNumber !== '') {
    $fieldsToUpdate[] = "IdNumber=?";
    $types .= 's';
    $values[] = $IdNumber;
}
if ($phoneNumber !== null && $phoneNumber !== '') {
    $fieldsToUpdate[] = "phoneNumber=?";
    $types .= 's';
    $values[] = $phoneNumber;
}


if (!empty($fieldsToUpdate)) {
    $sql = "UPDATE UserDetails SET " . implode(", ", $fieldsToUpdate) . " WHERE emailAddress=?";
    $stmt = $link->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $link->error);
    }

    $types .= 's';
    $values[] = $email;
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        echo "Record updated successfully for user ID: " . $email;
        header('Location: ../User_DashBoard/index.php');
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No fields to update or missing email.";
}

$link->close();
?>
