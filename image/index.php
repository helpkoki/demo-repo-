<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Upload</title>
</head>
<body>
    <h1>Upload or Take an Image</h1>
    <form action="../business-logic/image_upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" accept="image/*" capture="camera" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
