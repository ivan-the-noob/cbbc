<?php
session_start();


include '../../../db.php';

// Pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Search functionality
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

// Add pagination
$query .= " ORDER BY name LIMIT $offset, $records_per_page";

// Execute queries
$result = $conn->query($query);
$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $records_per_page);
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
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
                        
                        <!-- Status Filter -->
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
                            <th>Status</th>
                            <th>Date Baptized</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $counter = $offset + 1; ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $counter ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= $row['age'] ?: 'N/A' ?></td>
                                    <td><?= htmlspecialchars(substr($row['address'], 0, 50)) . (strlen($row['address']) > 50 ? '...' : '') ?></td>
                                    <td><?= htmlspecialchars($row['contact_no']) ?: 'N/A' ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= htmlspecialchars($row['type']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $row['active_inactive'] == 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= htmlspecialchars($row['active_inactive']) ?>
                                        </span>
                                    </td>
                                    <td><?= $row['date_baptized'] ? date('M d, Y', strtotime($row['date_baptized'])) : 'N/A' ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-btn" data-id="<?= $row['id'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="<?= $row['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
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
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Add/Edit Information</h5>
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
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="viewDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // View button click
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                fetch('../../function/php/get_information.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const info = data.data;
                            const details = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> ${info.name}</p>
                                        <p><strong>Age:</strong> ${info.age || 'N/A'}</p>
                                        <p><strong>Birthday:</strong> ${info.birthday || 'N/A'}</p>
                                        <p><strong>Contact No:</strong> ${info.contact_no || 'N/A'}</p>
                                        <p><strong>Type:</strong> <span class="badge bg-primary">${info.type}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> <span class="badge bg-info">${info.status}</span></p>
                                        <p><strong>Active:</strong> <span class="badge ${info.active_inactive === 'Active' ? 'bg-success' : 'bg-secondary'}">${info.active_inactive}</span></p>
                                        <p><strong>Date Saved:</strong> ${info.date_saved || 'N/A'}</p>
                                        <p><strong>Date Baptized:</strong> ${info.date_baptized || 'N/A'}</p>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <p><strong>Address:</strong></p>
                                        <p>${info.address || 'N/A'}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><strong>Occupation:</strong> ${info.occupation || 'N/A'}</p>
                                    </div>
                                </div>
                            `;
                            document.getElementById('viewDetails').innerHTML = details;
                            new bootstrap.Modal(document.getElementById('viewModal')).show();
                        }
                    });
            });
        });
        
        // Edit button click
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                fetch('../../function/php/get_information.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const info = data.data;
                            
                            // Fill form
                            document.getElementById('info_id').value = info.id;
                            document.getElementById('name').value = info.name;
                            document.getElementById('age').value = info.age || '';
                            document.getElementById('birthday').value = info.birthday || '';
                            document.getElementById('contact_no').value = info.contact_no || '';
                            document.getElementById('address').value = info.address || '';
                            document.getElementById('occupation').value = info.occupation || '';
                            document.getElementById('type').value = info.type;
                            document.getElementById('status').value = info.status;
                            document.getElementById('active_inactive').value = info.active_inactive;
                            document.getElementById('date_saved').value = info.date_saved || '';
                            document.getElementById('date_baptized').value = info.date_baptized || '';
                            
                            // Show modal
                            document.getElementById('infoModalLabel').textContent = 'Edit Information';
                            new bootstrap.Modal(document.getElementById('infoModal')).show();
                        }
                    });
            });
        });
        
        // Delete button click
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                if (confirm('Are you sure you want to delete this record?')) {
                    fetch('../../function/php/delete_information.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + id
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error deleting record');
                        }
                    });
                }
            });
        });
        
        // Add new button
        document.querySelector('[data-bs-target="#addModal"]').addEventListener('click', function() {
            document.getElementById('infoForm').reset();
            document.getElementById('info_id').value = '';
            document.getElementById('infoModalLabel').textContent = 'Add New Information';
        });
        
        // Form submission
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
                    location.reload();
                } else {
                    alert('Error saving data');
                }
            });
        });
    </script>
    
    <script src="../../function/script/toggle-menu.js"></script>
</body>
</html>