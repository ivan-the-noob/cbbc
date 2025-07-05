<?php
require '../../../../db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $image = $_FILES['image'];

     if ($image['error'] == 0) {
        $imageName = basename($image['name']);
        $targetDir = "../../../../assets/uploads/"; 
        $targetFile = $targetDir . $imageName;
        
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO images (image_url, role) VALUES (?, ?)");
            $stmt->bind_param("ss", $imageName, $role); 

            if ($stmt->execute()) {
               header('Location: ../../web/gallery.php');
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "Please select a valid image file.";
    }
}

$conn->close();
?>
