<?php
include '../../../db.php';

$recordsPerPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);

$offset = ($page - 1) * $recordsPerPage;

$sql = "SELECT * FROM images  LIMIT $recordsPerPage OFFSET $offset";
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
     $formattedDate = date("F j, Y", strtotime($row['created_at']));
    echo "<td><img src='../../../assets/uploads/" . htmlspecialchars($row['image_url']) . "' alt='Image' style='cursor: pointer;' class='img-thumbnail' width='100' data-bs-toggle='modal' data-bs-target='#imageModals' data-bs-img-src='../../../assets/uploads/" . htmlspecialchars($row['image_url']) . "'></td>";
      echo "
    <div class='modal fade' id='imageModals' tabindex='-1' aria-labelledby='imageModalsLabel' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='imageModalsLabel'>Zoomed Image</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <img id='zoomedImage' src='' alt='Zoomed Image' class='img-fluid h-100 w-100'>
                </div>
            </div>
        </div>
    </div>";
        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
    echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
    
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



