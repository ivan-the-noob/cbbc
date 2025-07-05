<?php
include '../../../db.php';

$recordsPerPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);

$offset = ($page - 1) * $recordsPerPage;

$sql = "SELECT * FROM contact LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
    $count = $offset + 1;
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        echo "<tr>";
        
        // Display count number
        echo "<td>$count</td>";
        
        // Display name
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        
        // Display Facebook name
        echo "<td>" . htmlspecialchars($row['facebook_name']) . "</td>";
        
        // Display message
        echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>"; // Use nl2br to preserve line breaks in the message
        
        // Add delete button
        echo "<td>
                <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal$id'>Delete</button>
              </td>";
        
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

<script>
    // Listen for the modal opening event
    document.addEventListener('DOMContentLoaded', function () {
        var modals = document.querySelectorAll('.modal');
        
        modals.forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function (event) {
                var triggerElement = event.relatedTarget; // Element that triggered the modal
                var imgSrc = triggerElement.getAttribute('data-bs-img-src'); // Get the image source
                
                // Set the source of the zoomed image in the modal
                var zoomedImage = modal.querySelector('#zoomedImage');
                zoomedImage.src = imgSrc;
            });
        });
    });
</script>






<!-- Include SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>



