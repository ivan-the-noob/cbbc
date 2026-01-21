<?php
// update_information.php
include '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $field = $conn->real_escape_string($_POST['field']);
    $value = $conn->real_escape_string($_POST['value']);
    
    // Validate allowed fields
    $allowed_fields = ['status', 'active_inactive', 'type'];
    
    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['success' => false, 'message' => 'Invalid field']);
        exit;
    }
    
    // Update the field
    $sql = "UPDATE information SET $field = '$value' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>