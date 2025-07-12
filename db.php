<?php
$servername = "localhost";
$username = "u373116035_cbbc"; 
$password = "#BakitCentral23";    
$dbname = "u373116035_cbbc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
