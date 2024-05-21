<?php 
class myTools{

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