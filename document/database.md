#Login pages table less call it user

#ID
id INT AUTO_INCREMENT PRIMARY KEY: This creates an auto-incrementing integer primary key named id.
#FIRSTNAME
firstName VARCHAR(50) NOT NULL: This creates a column for the first name, allowing up to 50 characters and not accepting NULL values.
#LASTNAME
lastName VARCHAR(50) NOT NULL: This creates a column for the last name, allowing up to 50 characters and not accepting NULL values.
#BirthDay
birthday DATE NOT NULL: This creates a column for the birthday date and does not accept NULL values.
#Gender
gender ENUM('female', 'male', 'other') NOT NULL: This creates a column for gender with allowed values 'female', 'male', and 'other', and does not accept NULL values.
#EmailAddres
emailAddress VARCHAR(100) NOT NULL: This creates a column for the email address, allowing up to 100 characters and not accepting NULL values.
#PhoneNumber
phoneNumber VARCHAR(10) NOT NULL: This creates a column for the phone number, allowing exactly 10 characters and not accepting NULL values.
#IDNAME
idNumber VARCHAR(10) NOT NULL: This creates a column for the ID number, allowing exactly 10 characters and not accepting NULL values.

CREATE TABLE UserDetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    birthday DATE NOT NULL,
    gender ENUM('female', 'male', 'other') NOT NULL,
    emailAddress VARCHAR(100) NOT NULL,
    phoneNumber VARCHAR(10) NOT NULL,
    idNumber VARCHAR(10) NOT NULL
);


