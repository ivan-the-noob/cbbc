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
    echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>
            <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editModal$id'>Edit</button>
            <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal$id'>Delete</button>
          </td>";
    echo "</tr>";

    // Edit Modal
    echo "<div class='modal fade' id='editModal$id' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered modal-sm' role='document'>
              <div class='modal-content'>
                <div class='modal-header d-flex justify-content-between'>
                  <h5 class='modal-title' id='editModalLabel'>Edit User</h5>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>
                <div class='modal-body'>
                  <form action='../function/php/edit_user.php' method='POST'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <div class='form-group'>
                      <label for='first_name'>First Name</label>
                      <input type='text' class='form-control' id='first_name' name='first_name' value='" . htmlspecialchars($row['first_name']) . "' required>
                    </div>
                    <div class='form-group'>
                      <label for='last_name'>Last Name</label>
                      <input type='text' class='form-control' id='last_name' name='last_name' value='" . htmlspecialchars($row['last_name']) . "' required>
                    </div>
                    <div class='form-group'>
                      <label for='email'>Email</label>
                      <input type='email' class='form-control' id='email' name='email' value='" . htmlspecialchars($row['email']) . "' required>
                    </div>
                    <button type='submit' class='mt-2 btn btn-primary'>Save changes</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";

        // Delete Modal
        echo "<div class='modal fade' id='deleteModal$id' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered modal-sm' role='document'>
                <div class='modal-content'>
                    <div class='modal-header d-flex justify-content-between'>
                    <h5 class='modal-title' id='deleteModalLabel'>Confirm Deletion</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>
                    <div class='modal-body'>
                    Are you sure you want to delete this user?
                    </div>
                    <div class='modal-footer'>
                    <form action='../function/php/delete_user.php' method='POST'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                        <button type='submit' class='btn btn-danger'>Delete</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>";
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



