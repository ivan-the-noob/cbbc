<?php
require_once '../db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to send JSON error response
function sendError($message, $code = 500, $details = null) {
    http_response_code($code);
    $response = [
        'success' => false,
        'message' => $message
    ];
    if ($details !== null) {
        $response['details'] = $details;
    }
    echo json_encode($response);
    exit;
}

// Function to send JSON success response
function sendSuccess($data, $message = '') {
    $response = [
        'success' => true,
        'data' => $data,
        'count' => count($data),
        'message' => $message
    ];
    echo json_encode($response);
    exit;
}

try {
    // Check database connection
    if (!$conn) {
        sendError('Database connection failed', 500, mysqli_connect_error());
    }
    
    // Check if mysqli extension is loaded
    if (!function_exists('mysqli_connect')) {
        sendError('MySQLi extension not loaded', 500);
    }
    
    // Get search parameter with validation
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    // Build query
    $query = "SELECT * FROM ppt_submissions WHERE 1=1";
    
    if (!empty($search)) {
        // Validate search term (alphanumeric and basic symbols only)
        if (!preg_match('/^[a-zA-Z0-9\s\-\.\,\'\"]+$/', $search) && !empty($search)) {
            sendError('Invalid search characters', 400);
        }
        
        // Sanitize search term
        $searchTerm = "%" . mysqli_real_escape_string($conn, $search) . "%";
        $query .= " AND (title LIKE '$searchTerm' OR singer LIKE '$searchTerm')";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    // Execute query
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        sendError('Query failed', 500, [
            'error' => mysqli_error($conn),
            'query' => $query
        ]);
    }
    
    // Fetch all results
    $ppts = [];
    while ($row = mysqli_fetch_assoc($result)) {
    // Sanitize output data
    $row['title'] = htmlspecialchars($row['title'] ?? '', ENT_QUOTES, 'UTF-8');
    $row['singer'] = htmlspecialchars($row['singer'] ?? '', ENT_QUOTES, 'UTF-8');
    $row['ppt_filename'] = htmlspecialchars($row['ppt_filename'] ?? '', ENT_QUOTES, 'UTF-8');
    $row['status'] = htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8');
    $row['created_at'] = $row['created_at'] ?? '';
    $row['timestamp'] = $row['timestamp'] ?? '';
    
    // Create file_path if ppt_filename exists
    if (!empty($row['ppt_filename'])) {
        $row['file_path'] = 'uploads/' . $row['ppt_filename'];
    } else {
        $row['file_path'] = '';
    }
    
    // Use ppt_filename as file_name
    $row['file_name'] = $row['ppt_filename'];
    
    $ppts[] = $row;
}
    
    // Free result
    mysqli_free_result($result);
    
    sendSuccess($ppts, 'PPT data retrieved successfully');
    
} catch (Exception $e) {
    sendError('An unexpected error occurred', 500, [
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} finally {
    // Close connection if it exists
    if (isset($conn) && $conn) {
        mysqli_close($conn);
    }
}
?>