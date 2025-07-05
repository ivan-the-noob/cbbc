<?php
include '../../../db.php';

// Define the number of records per page
$recordsPerPage = 10;

// Determine the current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure the page is at least 1

// Calculate the offset
$offset = ($page - 1) * $recordsPerPage;

// Fetch the records with a LIMIT and OFFSET
$sql = "SELECT * FROM users WHERE role = 'member' LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $count = $offset + 1;
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            echo "<tr>";
            echo "<td>$count</td>";
            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";

           
            echo "</tr>";
            $count++;
        }
    } else {
        echo "<tr><td colspan='4'>No users found</td></tr>";
    }
    $result->free();
}

// Get the total number of records
$totalSql = "SELECT COUNT(*) as total FROM users WHERE role = 'member'";
$totalResult = $conn->query($totalSql);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $recordsPerPage);

$totalResult->free();
?>





<!-- Include SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>



