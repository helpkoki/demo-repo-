<?php
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class
require_once '../Model/myTools.php'; // Include the User class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = $_FILES["image"]["tmp_name"];
        $imageName = $_FILES["image"]["name"];
        $imageType = $_FILES["image"]["type"];
        $imageSize = $_FILES["image"]["size"];
        $user_id  =$_SESSION['user_id'] ;
        // Read image file content into a variable
        $imageContent = addslashes(file_get_contents($image));

        // Insert image into database
        $sql = "INSERT INTO images (image, image_type, image_size, image_name ,user_id) VALUES ('$imageContent', '$imageType', '$imageSize', '$imageName' ,'$user_id')";
        if ($link->query($sql) === TRUE) {
            echo "Image uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    } else {
        echo "Error: " . $_FILES["image"]["error"];
    }
}

$link->close();
?>
