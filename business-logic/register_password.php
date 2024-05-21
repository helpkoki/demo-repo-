<?php
session_start(); // Start the session

// Include the database connection script
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class
require_once '../Model/myTools.php'; // Include the User class


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if User is set in session
    
    if (isset($_SESSION['email'])) {
        $tool = new myTools();
        $user = $tool->getUserByEmail($link ,$_SESSION['email']);

        // Ensure $user is a valid User object
        
            // Get form data
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $comfirmpassword = isset($_POST['comfirmpassword']) ? $_POST['comfirmpassword'] : '';

            // Check if passwords match
            if ($password === $comfirmpassword) {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Get the email from the session user object
                $email =$_SESSION['email'];

                // Retrieve the user ID from the UserDetails table
                $stmt = $link->prepare("SELECT id FROM UserDetails WHERE emailAddress = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($userId);
                $stmt->fetch();
                $stmt->close();

                if (!$userId) {
                    echo "Error: User not found.";
                    exit();
                }

                // Insert into the Login table
                $stmt = $link->prepare("INSERT INTO Login (user_id, password, email) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $userId, $hashedPassword, $email);

                if ($stmt->execute()) {
                    echo "Password registered successfully.";
                    header("Location: ../index.html");
                   // header ("Location: ../User_DashBoard/index.php");
                } else {
                    echo "Error: " . $stmt->error;
                }

                // Close the statement and connection
                $stmt->close();
                $link->close();
            } else {
                // If passwords do not match, redirect back to the password form
                header("Location: ../password.html");
                exit();
            }
        } 
    } else {
        echo "No user is registered.";
        exit();
    }

?>
