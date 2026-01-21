<?php
// save_information.php
include '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle both insert and update
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = $conn->real_escape_string($_POST['name']);
    $age = !empty($_POST['age']) ? (int)$_POST['age'] : null;
    $address = $conn->real_escape_string($_POST['address']);
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : null;
    $contact_no = !empty($_POST['contact_no']) ? $conn->real_escape_string($_POST['contact_no']) : null;
    $occupation = !empty($_POST['occupation']) ? $conn->real_escape_string($_POST['occupation']) : null;
    $date_saved = !empty($_POST['date_saved']) ? $_POST['date_saved'] : null;
    $date_baptized = !empty($_POST['date_baptized']) ? $_POST['date_baptized'] : null;
    $status = $conn->real_escape_string($_POST['status']);
    $active_inactive = $conn->real_escape_string($_POST['active_inactive']);
    $type = $conn->real_escape_string($_POST['type']);
    
    if ($id > 0) {
        // Update existing record
        $sql = "UPDATE information SET 
                name = '$name',
                age = " . ($age ? $age : 'NULL') . ",
                address = '$address',
                birthday = " . ($birthday ? "'$birthday'" : 'NULL') . ",
                contact_no = " . ($contact_no ? "'$contact_no'" : 'NULL') . ",
                occupation = " . ($occupation ? "'$occupation'" : 'NULL') . ",
                date_saved = " . ($date_saved ? "'$date_saved'" : 'NULL') . ",
                date_baptized = " . ($date_baptized ? "'$date_baptized'" : 'NULL') . ",
                status = '$status',
                active_inactive = '$active_inactive',
                type = '$type'
                WHERE id = $id";
        $message = "Record updated successfully";
    } else {
        // Insert new record
        $sql = "INSERT INTO information (name, age, address, birthday, contact_no, occupation, date_saved, date_baptized, status, active_inactive, type)
                VALUES (
                    '$name',
                    " . ($age ? $age : 'NULL') . ",
                    '$address',
                    " . ($birthday ? "'$birthday'" : 'NULL') . ",
                    " . ($contact_no ? "'$contact_no'" : 'NULL') . ",
                    " . ($occupation ? "'$occupation'" : 'NULL') . ",
                    " . ($date_saved ? "'$date_saved'" : 'NULL') . ",
                    " . ($date_baptized ? "'$date_baptized'" : 'NULL') . ",
                    '$status',
                    '$active_inactive',
                    '$type'
                )";
        $message = "Record added successfully";
    }
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => $message]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>