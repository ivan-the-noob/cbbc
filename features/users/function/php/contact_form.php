<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require '../../../../db.php';
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $facebook_name = mysqli_real_escape_string($conn, $_POST['facebook_name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact (name, facebook_name, message) VALUES ('$name', '$facebook_name', '$message')";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../../../../index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


    $conn->close();
}
?>
