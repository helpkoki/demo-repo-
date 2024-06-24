<?php
session_start(); // Start the session

// Include the database connection script
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class (adjust path as necessary)

// Function to save client information
function saveClient($link, $emailAddress) {
    // Prepare a statement to retrieve user details by email address
    $userSql = "SELECT id FROM UserDetails WHERE emailAddress = ?";
    $userStmt = $link->prepare($userSql);
    $userStmt->bind_param('s', $emailAddress);
    $userStmt->execute();
    $resultUser = $userStmt->get_result();
    
    $balance = 0 ;

    if ($resultUser->num_rows > 0) {
        $loginDataId = $resultUser->fetch_assoc();
        $user_id_client = $loginDataId['id'];

        // Insert into Client table
        $stmt = $link->prepare("INSERT INTO Client (user_id,balance) VALUES (?,?)");
        $stmt->bind_param("id", $user_id_client,$balance);

        if ($stmt->execute()) {
            // Client saved successfully
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // User not found in UserDetails table
        // Handle this case as per your application's logic
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data (sanitize and validate as needed)
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $birthday = isset($_POST['birthdayDate']) ? $_POST['birthdayDate'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $phone = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
    $email = isset($_POST['emailAddress']) ? $_POST['emailAddress'] : '';
    $idNumber = isset($_POST['IdNumber']) ? $_POST['IdNumber'] : '';

    // Create a new User object
    $user = new User($firstName, $lastName, $birthday, $gender, $email, $phone, $idNumber);

    // Save the user to the session
    $_SESSION['User'] = $user;
    $_SESSION['email'] = $email;

    // Check if the user already exists
    if ($user->userExists($link)) {
        echo "Error: User with this email or ID number already exists.";
        header('Location: ../sign.html');
        exit();
    } else {
        // Save the user to the database
        $result = $user->saveToDatabase($link);
        
        // Save client information
        saveClient($link, $email);

        // Output the result (you might want to handle this differently)
        echo $result;
        
        // Redirect to password.html after successful registration
        header('Location: ../password.html');
        exit();
    }

    // Close the database connection
    $link->close();
} else {
    echo "Invalid request method.";
}
?>
