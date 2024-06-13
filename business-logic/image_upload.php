<?php
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class
require_once '../Model/myTools.php'; // Include the User class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        // Handling uploaded image
        $image = $_FILES["image"]["tmp_name"];
        $imageName = $_FILES["image"]["name"];
        $imageType = $_FILES["image"]["type"];
        $imageSize = $_FILES["image"]["size"];
        $imageContent = addslashes(file_get_contents($image));
		session_start();
 $user_id  =$_SESSION['user_id'] ;

        $sql = "INSERT INTO images (image, image_type, image_size, image_name,user_id) VALUES ('$imageContent', '$imageType', '$imageSize', '$imageName','user_id')";
        if ($link->query($sql) === TRUE) {
            echo "Image uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    } elseif (isset($_POST['capturedImage'])) {
        // Handling captured image
        $dataURL = $_POST['capturedImage'];
        $parts = explode(',', $dataURL);
        $imageTypeAux = explode(';', $parts[0]);
        $imageType = explode(':', $imageTypeAux[0])[1];
        $imageContent = base64_decode($parts[1]);

        $sql = "INSERT INTO images (image, image_type, image_size, image_name) VALUES ('" . addslashes($imageContent) . "', '$imageType', '". strlen($imageContent) . "', 'captured_image.png')";
        if ($link->query($sql) === TRUE) {
            echo "Captured image uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    } else {
        echo "Error: No image uploaded or captured.";
    }
}

$link->close();
?>
