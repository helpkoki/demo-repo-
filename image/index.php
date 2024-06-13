<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Upload</title>
    <style>
        video, canvas {
            display: block;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Upload or Take an Image</h1>
    <form id="uploadForm" action="../business-logic/image_upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" accept="image/*">
        <button type="submit">Upload</button>
    </form>
    <h2>Or Take a Picture</h2>
    <video id="video" width="320" height="240" autoplay></video>
    <button id="snap">Capture Photo</button>
    <canvas id="canvas" width="320" height="240"></canvas>
    <form id="captureForm" action="../business-logic/image_upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="capturedImage" id="capturedImage">
        <button type="submit">Upload Captured Photo</button>
    </form>
    <script>
        // Elements for taking the snapshot
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const snapButton = document.getElementById('snap');
        const captureForm = document.getElementById('captureForm');
        const capturedImageInput = document.getElementById('capturedImage');

        // Get access to the camera
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then((stream) => {
                video.srcObject = stream;
                video.play();
            });
        }

        // Trigger photo capture
        snapButton.addEventListener('click', function () {
            context.drawImage(video, 0, 0, 320, 240);
            const dataURL = canvas.toDataURL('image/png');
            capturedImageInput.value = dataURL;
        });
    </script>
</body>
</html>
