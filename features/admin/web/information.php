<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../users/web/api/login.php");
    exit();
}

include '../../../db.php';

// Remove pagination to show all records
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Filter functionality
$filters = [];
$filter_sql = "";

if (isset($_GET['age_min']) && $_GET['age_min'] !== '') {
    $filters[] = "age >= " . (int)$_GET['age_min'];
}
if (isset($_GET['age_max']) && $_GET['age_max'] !== '') {
    $filters[] = "age <= " . (int)$_GET['age_max'];
}
if (isset($_GET['active_inactive']) && $_GET['active_inactive'] !== '') {
    $filters[] = "active_inactive = '" . $conn->real_escape_string($_GET['active_inactive']) . "'";
}
if (isset($_GET['type']) && $_GET['type'] !== '') {
    $filters[] = "type = '" . $conn->real_escape_string($_GET['type']) . "'";
}

if (!empty($filters)) {
    $filter_sql = " WHERE " . implode(' AND ', $filters);
}

// Build query
$query = "SELECT * FROM information";
$count_query = "SELECT COUNT(*) as total FROM information";

if (!empty($search)) {
    $search_term = $conn->real_escape_string($search);
    $where = " WHERE name LIKE '%$search_term%' OR address LIKE '%$search_term%'";
    if (!empty($filter_sql)) {
        $where .= " AND " . substr($filter_sql, 7);
    }
    $query .= $where;
    $count_query .= $where;
} else if (!empty($filter_sql)) {
    $query .= $filter_sql;
    $count_query .= $filter_sql;
}

// No pagination - show all
$query .= " ORDER BY name";

// Execute queries
$result = $conn->query($query);
$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information Management | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/users.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style>
        .status-select {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;
            width: 100px;
        }
        
        .status-select:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .badge {
            cursor: pointer;
        }
        
        .table-wrapper {
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .table th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 1;
        }
        
        .active-inactive-select {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;
            width: 100px;
        }
        
        .type-select {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;
            width: 120px;
        }
    </style>
</head>
<body>
    <!-- Navigation Links -->
    <div class="navbar flex-column bg-white shadow-sm p-3 collapse d-md-flex" id="navbar">
        <div class="navbar-links">
            <a class="navbar-brand d-none d-md-block logo-container" href="#">
                <img src="../../../bg.png" alt="Logo">
            </a>
            
            <a href="users.php">
                <span>Users</span>
            </a>
            
            <a href="information.php" class="navbar-highlight">
                <span>Information</span>
            </a>
            
            <a href="gallery.php">
                <span>Gallery</span>
            </a>
            
            <a href="contact.php">
                <span>Contact</span>
            </a>
        </div>
    </div>
    
    <div class="content flex-grow-1">
        <div class="header">
            <button class="navbar-toggler d-block d-md-none" type="button" onclick="toggleMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="stroke: black; fill: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            
            <div class="profile-admin">
                <div class="dropdown">
                    <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../../../bg.png" style="width: 40px; height: 40px; object-fit: cover;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../authentication/function/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="app-req">
            <h3>Information Management</h3>
            
            <!-- Search and Filters -->
            <div class="walk-in px-lg-5">
                <form method="GET" action="" class="mb-4">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <div class="search-bars">
                                <i class="fa fa-magnifying-glass"></i>
                                <input type="text" class="form-control" name="search" placeholder="Search by name or address..." 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>
                        
                        <!-- Age Filter -->
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="age_min" placeholder="Min Age" 
                                   value="<?= isset($_GET['age_min']) ? htmlspecialchars($_GET['age_min']) : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="age_max" placeholder="Max Age" 
                                   value="<?= isset($_GET['age_max']) ? htmlspecialchars($_GET['age_max']) : '' ?>">
                        </div>
                        
                        <!-- Active/Inactive Filter -->
                        <div class="col-md-2">
                            <select class="form-control" name="active_inactive">
                                <option value="">All Status</option>
                                <option value="Active" <?= (isset($_GET['active_inactive']) && $_GET['active_inactive'] == 'Active') ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= (isset($_GET['active_inactive']) && $_GET['active_inactive'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="col-md-2">
                            <select class="form-control" name="type">
                                <option value="">All Types</option>
                                <option value="Women" <?= (isset($_GET['type']) && $_GET['type'] == 'Women') ? 'selected' : '' ?>>Women</option>
                                <option value="Men" <?= (isset($_GET['type']) && $_GET['type'] == 'Men') ? 'selected' : '' ?>>Men</option>
                                <option value="Young People" <?= (isset($_GET['type']) && $_GET['type'] == 'Young People') ? 'selected' : '' ?>>Young People</option>
                                <option value="Young Pro" <?= (isset($_GET['type']) && $_GET['type'] == 'Young Pro') ? 'selected' : '' ?>>Young Pro</option>
                                <option value="Children" <?= (isset($_GET['type']) && $_GET['type'] == 'Children') ? 'selected' : '' ?>>Children</option>
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <a href="information.php" class="btn btn-secondary w-100">Clear Filters</a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info p-2 mb-0">
                                <small><i class="fas fa-info-circle"></i> Showing all <?= $total_rows ?> records. Use filters to narrow results.</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Table -->
            <div class="table-wrapper px-lg-5">
                <table class="table table-hover table-remove-borders">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Contact No</th>
                            <th>Type</th>
                            <th>Active/Inactive</th>
                            <th>Status</th>
                            <th>Date Baptized</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $counter = 1; ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr data-id="<?= $row['id'] ?>">
                                    <td><?= $counter ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= $row['age'] ?: 'N/A' ?></td>
                                    <td><?= htmlspecialchars(substr($row['address'], 0, 50)) . (strlen($row['address']) > 50 ? '...' : '') ?></td>
                                    <td><?= htmlspecialchars($row['contact_no']) ?: 'N/A' ?></td>
                                    <td>
                                        <select class="type-select" data-field="type" data-id="<?= $row['id'] ?>">
                                            <option value="Women" <?= $row['type'] == 'Women' ? 'selected' : '' ?>>Women</option>
                                            <option value="Men" <?= $row['type'] == 'Men' ? 'selected' : '' ?>>Men</option>
                                            <option value="Young People" <?= $row['type'] == 'Young People' ? 'selected' : '' ?>>Young People</option>
                                            <option value="Young Pro" <?= $row['type'] == 'Young Pro' ? 'selected' : '' ?>>Young Pro</option>
                                            <option value="Children" <?= $row['type'] == 'Children' ? 'selected' : '' ?>>Children</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="active-inactive-select" data-field="active_inactive" data-id="<?= $row['id'] ?>">
                                            <option value="Active" <?= $row['active_inactive'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= $row['active_inactive'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="status-select" data-field="status" data-id="<?= $row['id'] ?>">
                                            <option value="Single" <?= $row['status'] == 'Single' ? 'selected' : '' ?>>Single</option>
                                            <option value="Married" <?= $row['status'] == 'Married' ? 'selected' : '' ?>>Married</option>
                                            <option value="Separated" <?= $row['status'] == 'Separated' ? 'selected' : '' ?>>Separated</option>
                                            <option value="Widowed" <?= $row['status'] == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                        </select>
                                    </td>
                                    <td><?= $row['date_baptized'] ? date('M d, Y', strtotime($row['date_baptized'])) : 'N/A' ?></td>
                                </tr>
                                <?php $counter++; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Add New Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="infoForm" action="../../function/php/save_information.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="info_id" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name *</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Age</label>
                                <input type="number" class="form-control" name="age" id="age">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Birthday</label>
                                <input type="date" class="form-control" name="birthday" id="birthday">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contact No</label>
                                <input type="text" class="form-control" name="contact_no" id="contact_no">
                            </div>
                            <div class="col-12 mb-3">
                                <label>Address</label>
                                <textarea class="form-control" name="address" id="address" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Occupation</label>
                                <input type="text" class="form-control" name="occupation" id="occupation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Type *</label>
                                <select class="form-control" name="type" id="type" required>
                                    <option value="Women">Women</option>
                                    <option value="Men">Men</option>
                                    <option value="Young People">Young People</option>
                                    <option value="Young Pro">Young Pro</option>
                                    <option value="Children">Children</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Active/Inactive</label>
                                <select class="form-control" name="active_inactive" id="active_inactive">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Date Saved</label>
                                <input type="date" class="form-control" name="date_saved" id="date_saved">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Date Baptized</label>
                                <input type="date" class="form-control" name="date_baptized" id="date_baptized">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to update status, type, or active_inactive immediately
        function updateField(id, field, value) {
            $.ajax({
                url: '../../function/php/update_information.php',
                type: 'POST',
                data: {
                    id: id,
                    field: field,
                    value: value
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            // Show success message
                            swal({
                                title: "Success!",
                                text: data.message,
                                icon: "success",
                                timer: 1000,
                                buttons: false
                            });
                        } else {
                            swal("Error!", data.message || "Failed to update", "error");
                        }
                    } catch (e) {
                        swal("Error!", "Invalid response from server", "error");
                    }
                },
                error: function() {
                    swal("Error!", "Failed to update. Please try again.", "error");
                }
            });
        }

        // Event listener for status dropdown changes
        $(document).on('change', '.status-select', function() {
            const id = $(this).data('id');
            const value = $(this).val();
            updateField(id, 'status', value);
        });

        // Event listener for active/inactive dropdown changes
        $(document).on('change', '.active-inactive-select', function() {
            const id = $(this).data('id');
            const value = $(this).val();
            updateField(id, 'active_inactive', value);
        });

        // Event listener for type dropdown changes
        $(document).on('change', '.type-select', function() {
            const id = $(this).data('id');
            const value = $(this).val();
            updateField(id, 'type', value);
        });

        // Add new button
        document.querySelector('[data-bs-target="#addModal"]').addEventListener('click', function() {
            document.getElementById('infoForm').reset();
            document.getElementById('info_id').value = '';
            document.getElementById('infoModalLabel').textContent = 'Add New Information';
        });

        // Form submission for adding new record
        document.getElementById('infoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                        timer: 1500,
                        buttons: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    swal("Error!", data.message || "Failed to save data", "error");
                }
            });
        });

        // Search functionality
        $('#search-input').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                if (text.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    </script>
    
    <!-- Toggle menu function -->
    <script>
        function toggleMenu() {
            const navbar = document.getElementById('navbar');
            if (navbar.classList.contains('collapse')) {
                navbar.classList.remove('collapse');
            } else {
                navbar.classList.add('collapse');
            }
        }
    </script>   
</body>
</html>