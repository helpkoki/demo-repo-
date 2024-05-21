<?php

class User {
    private $firstName;
    private $lastName;
    private $birthday;
    private $gender;
    private $email;
    private $phone;
    private $idNumber;

    public function __construct($firstName, $lastName, $birthday, $gender, $email, $phone, $idNumber) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->email = $email;
        $this->phone = $phone;
        $this->idNumber = $idNumber;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getIdNumber() {
        return $this->idNumber;
    }

    public function saveToDatabase($link) {
        $stmt = $link->prepare("INSERT INTO UserDetails (firstName, lastName, birthday, gender, emailAddress, phoneNumber, idNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $this->firstName, $this->lastName, $this->birthday, $this->gender, $this->email, $this->phone, $this->idNumber);

        if ($stmt->execute()) {
            return "New record created successfully";
        } else {
            return "Error: " . $stmt->error;
        }
    }

    public function userExists($link) {
        $stmt = $link->prepare("SELECT * FROM UserDetails WHERE emailAddress = ? OR idNumber = ?");
        $stmt->bind_param("ss", $this->email, $this->idNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

     // Static method to initialize a User object from the database using an email address
    
}

?>
