<?php

require '../../../db.php';
session_start();

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
    
    $sql = "SELECT profile_image FROM users WHERE email = '$userEmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profileImage = $row['profile_image'] ? $row['profile_image'] : 'dummy.png';
        $profileImagePath = "../../../assets/profile/" . $profileImage;
    } else {
        $profileImagePath = "../../../assets/profile/dummy.jpg";
    }
} else {
    $profileImagePath = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../../../nav.css">
    <link rel="stylesheet" href="../css/baptism.css">
</head>
<body>

   <section class="front_page position-relative">
        <div class="navbar-container">
        <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container bg-nav">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="stroke: black; fill: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                            <ul class="navbar-nav d-flex justify-content-center align-items-center">
                                <li class="nav-item">
                                      <a class="navbar-brand d-none d-lg-block" href="#">
                                            <img src="../../../assets/logo/cbbc.png" alt="Logo" width="80" height="80">
                                        </a>
                                </li>
                                <li class="nav-item links">
                                    <a class="nav-link" href="../../../index.php">Home</a>
                                </li>
                               
                               
                                <li class="nav-item links">
                                    <a class="nav-link" href="#">Gallery</a>
                                </li>
                                
                                <li class="nav-item dropdown">
                                <?php if ($profileImagePath): ?>
                                    <!-- Profile Image Dropdown -->
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="<?= $profileImagePath ?>" alt="Profile" class="img-fluid rounded-circle" style="border: 1px solid green; width: 40px; height: 40px;">
                                </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="../../../profile.php">My Profile</a></li>
                                        <li><a class="dropdown-item" href="../../../features/authentication/function/logout.php">Log Out</a></li>
                                    </ul>
                                <?php else: ?>
                                    <!-- If not logged in, show the Log In button -->
                                    <button class="nav-link log-in-button" onclick="window.location.href='login.php'">Log In</button>
                                <?php endif; ?>
                            </li>
                            </ul>
                            <div class="d-flex ml-auto">
                </nav>
        </div>
       <!-- <img src="../../../assets/fellowship/fellowship.jpg" class="fellow-bg img-fluid" alt=""> -->
    </section>

    <section class="gallery">
        <div class="container">
            <?php
            require '../../../db.php';
            $sql = "SELECT image_url FROM images WHERE role = 'fellowship'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div class="row">';
                while ($row = $result->fetch_assoc()) {
                    $imageUrl = "../../../assets/uploads/" . htmlspecialchars($row['image_url']);
                    echo "
                    <div class='col-md-4 mt-4'>
                        <img src='$imageUrl' class='brd img-fluid rounded' alt=''>
                    </div>";
                }
                echo '</div>';
            } else {
                echo "<p>No images found for 'Soulwinning' role.</p>";
            }
            ?>

        </div>

    </section>
    
</body>
</html>