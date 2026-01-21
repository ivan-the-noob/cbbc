<?php
include '../../../db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "SELECT * FROM information WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Format dates
        foreach (['birthday', 'date_saved', 'date_baptized'] as $date_field) {
            if ($row[$date_field]) {
                $row[$date_field] = date('Y-m-d', strtotime($row[$date_field]));
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $row
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Record not found']);
    }
}

$conn->close();
?>