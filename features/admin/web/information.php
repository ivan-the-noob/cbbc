<?php
session_start();

include '../../../db.php';

// Show all records (no pagination)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Filter functionality - FIXED: Each filter should work independently
$filters = [];

if (isset($_GET['age_min']) && $_GET['age_min'] !== '') {
    $filters[] = "age >= " . (int)$_GET['age_min'];
}
if (isset($_GET['age_max']) && $_GET['age_max'] !== '') {
    $filters[] = "age <= " . (int)$_GET['age_max'];
}
if (isset($_GET['active_inactive']) && $_GET['active_inactive'] !== '') {
    $filters[] = "active_inactive = '" . $conn->real_escape_string($_GET['active_inactive']) . "'";
}
if (isset($_GET['type']) && $_GET['type'] !== '' && $_GET['type'] !== 'Select') {
    $filters[] = "type = '" . $conn->real_escape_string($_GET['type']) . "'";
}
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $filters[] = "status = '" . $conn->real_escape_string($_GET['status']) . "'";
}

// Build WHERE clause
$where_conditions = [];
if (!empty($search)) {
    $search_term = $conn->real_escape_string($search);
    $where_conditions[] = "(name LIKE '%$search_term%' OR address LIKE '%$search_term%')";
}

// Add filters to WHERE conditions
if (!empty($filters)) {
    foreach ($filters as $filter) {
        $where_conditions[] = $filter;
    }
}

// Build final query
$query = "SELECT * FROM information";
$count_query = "SELECT COUNT(*) as total FROM information";

if (!empty($where_conditions)) {
    $where_sql = " WHERE " . implode(" AND ", $where_conditions);
    $query .= $where_sql;
    $count_query .= $where_sql;
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
        .type-select {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;
            width: 120px;
            cursor: pointer;
        }
        
        .type-select:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-single { background-color: #e3f2fd; color: #1976d2; }
        .status-married { background-color: #e8f5e9; color: #388e3c; }
        .status-separated { background-color: #fff3e0; color: #f57c00; }
        .status-widowed { background-color: #fce4ec; color: #c2185b; }
        
        .active-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .active-active { background-color: #d4edda; color: #155724; }
        .active-inactive { background-color: #f8d7da; color: #721c24; }
        
        .table-wrapper {
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .table th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 1;
            white-space: nowrap;
            color: #06850C !important;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .address-cell {
            max-width: 200px;
            word-wrap: break-word;
            white-space: normal;
        }
        
        .contact-no {
            font-family: monospace;
        }
        
        .date-cell {
            white-space: nowrap;
        }
        
        /* Make sure the address doesn't overflow */
        .table td:first-child {
            max-width: 250px;
        }
        
        /* Ensure proper text wrapping for address */
        .address-text {
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.2;
            font-size: 0.85rem;
            display: block;
            margin-top: 3px;
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
                        <div class="col-md-4">
                            <div class="search-bars">
                                <i class="fa fa-magnifying-glass"></i>
                                <input type="text" class="form-control" name="search" placeholder="Search by name..." 
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
                        <!-- Active/Inactive Filter -->
                        <div class="col-md-2">
                            <select class="form-control" name="active_inactive">
                                <option value="">Active/Inactive</option> <!-- Already correct -->
                                <option value="Active" <?= (isset($_GET['active_inactive']) && $_GET['active_inactive'] == 'Active') ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= (isset($_GET['active_inactive']) && $_GET['active_inactive'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="col-md-2">
                            <select class="form-control" name="type">
                                <option value="Select">Select Type</option>
                                <option value="Women" <?= (isset($_GET['type']) && $_GET['type'] == 'Women') ? 'selected' : '' ?>>Women</option>
                                <option value="Men" <?= (isset($_GET['type']) && $_GET['type'] == 'Men') ? 'selected' : '' ?>>Men</option>
                                <option value="Young People" <?= (isset($_GET['type']) && $_GET['type'] == 'Young People') ? 'selected' : '' ?>>Young People</option>
                                <option value="Young Pro" <?= (isset($_GET['type']) && $_GET['type'] == 'Young Pro') ? 'selected' : '' ?>>Young Pro</option>
                                <option value="Children" <?= (isset($_GET['type']) && $_GET['type'] == 'Children') ? 'selected' : '' ?>>Children</option>
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <select class="form-control" name="status">
                                <option value="">Status</option>
                                <option value="Single" <?= (isset($_GET['status']) && $_GET['status'] == 'Single') ? 'selected' : '' ?>>Single</option>
                                <option value="Married" <?= (isset($_GET['status']) && $_GET['status'] == 'Married') ? 'selected' : '' ?>>Married</option>
                                <option value="Separated" <?= (isset($_GET['status']) && $_GET['status'] == 'Separated') ? 'selected' : '' ?>>Separated</option>
                                <option value="Widowed" <?= (isset($_GET['status']) && $_GET['status'] == 'Widowed') ? 'selected' : '' ?>>Widowed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <!-- Action Buttons -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                        <div class="col-md-2">
                            <a href="information.php" class="btn btn-secondary w-100">Clear Filters</a>
                        </div>
                        <div class="col-md-2">
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
                            <th>Name / Address</th>
                            <th>Age</th>
                            <th>Birthday</th>
                            <th>Contact Number</th>
                            <th>Date Saved</th>
                            <th>Date Baptized</th>
                            <th>Status</th>
                            <th>Active/Inactive</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr data-id="<?= $row['id'] ?>">
                                    <td class="address-cell">
                                        <strong><?= htmlspecialchars($row['name']) ?></strong>
                                        <?php if(!empty($row['address'])): ?>
                                            <br><small class="text-muted address-text"><?= htmlspecialchars($row['address']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($row['age']): ?>
                                            <span class="badge bg-info"><?= $row['age'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="date-cell">
                                        <?php if($row['birthday']): ?>
                                            <?= date('M d, Y', strtotime($row['birthday'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="contact-no">
                                        <?php if(!empty($row['contact_no']) && $row['contact_no'] !== 'N/A'): ?>
                                            <?= htmlspecialchars($row['contact_no']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="date-cell">
                                        <?php if($row['date_saved']): ?>
                                            <?= date('M d, Y', strtotime($row['date_saved'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="date-cell">
                                        <?php if($row['date_baptized']): ?>
                                            <?= date('M d, Y', strtotime($row['date_baptized'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        switch($row['status']) {
                                            case 'Single': $status_class = 'status-single'; break;
                                            case 'Married': $status_class = 'status-married'; break;
                                            case 'Separated': $status_class = 'status-separated'; break;
                                            case 'Widowed': $status_class = 'status-widowed'; break;
                                            default: $status_class = 'status-single';
                                        }
                                        ?>
                                        <span class="status-badge <?= $status_class ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $active_class = $row['active_inactive'] == 'Active' ? 'active-active' : 'active-inactive';
                                        ?>
                                        <span class="active-badge <?= $active_class ?>">
                                            <?= htmlspecialchars($row['active_inactive']) ?>
                                        </span>
                                    </td>
                                   <td>
                                        <select class="type-select" data-id="<?= $row['id'] ?>">
                                            <option value="">Select Type</option>
                                            <option value="Women" <?= (!empty($row['type']) && $row['type'] == 'Women') ? 'selected' : '' ?>>Women</option>
                                            <option value="Men" <?= (!empty($row['type']) && $row['type'] == 'Men') ? 'selected' : '' ?>>Men</option>
                                            <option value="Young People" <?= (!empty($row['type']) && $row['type'] == 'Young People') ? 'selected' : '' ?>>Young People</option>
                                            <option value="Young Pro" <?= (!empty($row['type']) && $row['type'] == 'Young Pro') ? 'selected' : '' ?>>Young Pro</option>
                                            <option value="Children" <?= (!empty($row['type']) && $row['type'] == 'Children') ? 'selected' : '' ?>>Children</option>
                                        </select>
                                    </td>
                                </tr>
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
    
    <!-- Add New Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="infoForm" action="save_information.php" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Age</label>
                                <input type="number" class="form-control" name="age">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Birthday</label>
                                <input type="date" class="form-control" name="birthday">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" name="contact_no">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Date Saved</label>
                                <input type="date" class="form-control" name="date_saved">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Date Baptized</label>
                                <input type="date" class="form-control" name="date_baptized">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea class="form-control" name="address" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Active/Inactive</label>
                                <select class="form-control" name="active_inactive">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Type *</label>
                                <select class="form-control" name="type" required>
                                    <option value="Women">Women</option>
                                    <option value="Men">Men</option>
                                    <option value="Young People">Young People</option>
                                    <option value="Young Pro">Young Pro</option>
                                    <option value="Children">Children</option>
                                </select>
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
        // Function to update Type immediately
        function updateType(id, value) {
            $.ajax({
                url: 'update_information.php',
                type: 'POST',
                data: {
                    id: id,
                    field: 'type',
                    value: value
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            // Show success message
                            swal({
                                title: "Updated!",
                                text: "Type updated successfully",
                                icon: "success",
                                timer: 1000,
                                buttons: false
                            });
                        } else {
                            swal("Error!", data.message || "Failed to update type", "error");
                        }
                    } catch (e) {
                        swal("Error!", "Invalid response from server", "error");
                    }
                },
                error: function() {
                    swal("Error!", "Failed to update type. Please try again.", "error");
                }
            });
        }

        // Event listener for Type dropdown changes
        $(document).on('change', '.type-select', function() {
            const id = $(this).data('id');
            const value = $(this).val();
            updateType(id, value);
        });

        // Form submission for adding new record
        $('#infoForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
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
                    } catch (e) {
                        swal("Error!", "Invalid response from server", "error");
                    }
                },
                error: function() {
                    swal("Error!", "Failed to save data. Please try again.", "error");
                }
            });
        });

        // Search functionality
        $('input[name="search"]').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                const name = $(this).find('td:first-child strong').text().toLowerCase();
                if (name.includes(searchTerm)) {
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