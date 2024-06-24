<?php
class UserAuthenticator {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function verifyCredentials($email, $password) {
        $sql = "SELECT * FROM Login WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $loginData = $result->fetch_assoc();

        if ($loginData && password_verify($password, $loginData['password'])) {
            // Retrieve user details from UserDetails table using user_id
            $userSql = "SELECT * FROM UserDetails WHERE id = ?";
            $userStmt = $this->pdo->prepare($userSql);
            $userStmt->bind_param('s', $loginData['user_id']);
            $userStmt->execute();
            $resultUser = $userStmt->get_result();
            $loginDataId = $resultUser->fetch_assoc();
            
            if ($loginDataId) {
                return $loginDataId; // Return user details if credentials are verified
            }
        }
        return false; // Return false if credentials are not verified
    }
    

    public function initUserSession($user) {
        // Initialize user session based on retrieved user details
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['emailAddress'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];


        // Redirect user based on their role or any other relevant criteria
        // Replace these placeholders with actual redirection logic based on user's role
        header("Location: ../User_DashBoard/index.php");
       
      // echo "yes session started";
        exit;
    }

    

}
?>
