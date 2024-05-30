<?php
require_once '../database-config/config.php';
require_once '../Model/User.php'; // Include the User class
require_once '../Model/myTools.php'; // Include the User class


$sql = "SELECT id, image, image_type, image_name FROM images";
$result = $link->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<h2>" . $row["image_name"] . "</h2>";
        echo '<img src="data:' . $row["image_type"] . ';base64,' . base64_encode($row["image"]) . '" alt="' . $row["image_name"] . '">';
    }
} else {
    echo "No images found.";
}

$link->close();
?>
