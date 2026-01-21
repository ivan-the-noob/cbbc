
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/users.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

</head>

<body>
    <!--Navigation Links-->
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
             <a href="contact.php" class="navbar-highlight">
                <span>Contact</span>
            </a>
           
            </div>

        </div>
    </div>
    <!--Navigation Links End-->
    <div class="content flex-grow-1">
        <div class="header">
            <button class="navbar-toggler d-block d-md-none" type="button" onclick="toggleMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    style="stroke: black; fill: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
            <!--Notification and Profile Admin-->
            <div class="profile-admin">
                <div class="dropdown">
                    <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../../../bg.png"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../authentication/function/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--Notification and Profile Admin-->
        <div class="app-req">
            <h3>Contact</h3>
            <div class="walk-in px-lg-5">
                <div class="mb-3 x d-flex">
                    <div class="search">
                        <div class="search-bars">
                            <i class="fa fa-magnifying-glass"></i>
                            <input type="text" class="form-control" placeholder="Search..." id="search-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container add_button d-flex justify-content-end">
                
                    </div>

            </div>
            <div class="table-wrapper px-lg-5">
                <table class="table table-hover table-remove-borders">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Facebook Name</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">

                        <?php
                        include '../../../db.php';
                        include '../function/php/contact.php'
                        ?>

                    </tbody>
                </table>
                <!--Appointment Request Table End-->
            </div>
            <?php if ($totalRows >= 9): ?>
                <ul class="pagination justify-content-end mt-3 px-lg-5">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" data-page="prev"><</a>
                        </li>
                    <?php endif; ?>

                    <?php
                    // Determine page range to display
                    $startPage = max(1, $page - 1);
                    $endPage = min($totalPages, $startPage + 2);

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" data-page="next">></a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>

<script>
    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value;

        const xhr = new XMLHttpRequest();

        xhr.open('GET', '../../function/php/search/search_users.php?query=' + encodeURIComponent(searchTerm), true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                const results = JSON.parse(xhr.responseText);

                const tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = '';

                results.forEach((user, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <form action='../../function/php/delete_user.php' method='POST'>
                            <input type='hidden' name='user_id' value='${user.id}' />
                            <input type='submit' value='Delete' class='btn btn-danger' />
                        </form>
                    </td>
                `;
                    tableBody.appendChild(row);
                });
            }
        };

        // Send the request
        xhr.send();
    });
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmgygVeipMUsrtGeZPZ9UzXRmcVdheIqw&libraries=places">
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="../../function/script/toggle-menu.js"></script>
<script src="../../function/script/pagination.js"></script>
<script src="../../function/script/drop-down.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</html>