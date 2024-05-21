<?php 
class myTools{

              
    public function initUserSession($user) {
        // Initialize user session based on retrieved user details
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['emailAddress'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];

    }
    public function verifyCredentials($email, $password) {
        // Query to select password and user_id from Login table based on email
        $sql = "SELECT user_id, password FROM Login WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $loginData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($loginData && password_verify($password, $loginData['password'])) {
            // If password is verified, fetch user details from UserDetails table using user_id
            $userSql = "SELECT * FROM UserDetails WHERE id = ?";
            $userStmt = $this->pdo->prepare($userSql);
            $userStmt->execute([$loginData['user_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                return $user; // Return user details if credentials are verified
            }
        }
        return false; // Return false if credentials are not verified
    }
    
 function getUserByEmail($link, $email) {
    $stmt = $link->prepare("SELECT firstName, lastName, birthday, gender, emailAddress, phoneNumber, idNumber FROM UserDetails WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return new User($row['firstName'], $row['lastName'], $row['birthday'], $row['gender'], $row['emailAddress'], $row['phoneNumber'], $row['idNumber']);
    } else {
        return null; // or throw an exception
    }
}
}
?>