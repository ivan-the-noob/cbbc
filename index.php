<?php

require 'db.php';
session_start();

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
    
    $sql = "SELECT profile_image FROM users WHERE email = '$userEmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profileImage = $row['profile_image'] ? $row['profile_image'] : 'dummy.png';
        $profileImagePath = "assets/profile/" . $profileImage;
    } else {
        $profileImagePath = "assets/profile/dummy.jpg";
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
    <link rel="stylesheet" href="style.css">
      <link rel="stylesheet" href="nav.css">

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
                                    <img src="assets/logo/cbbc.png" alt="Logo" width="80" height="80">
                                </a>
                            </li>
                            <li class="nav-item links">
                                <a class="nav-link" href="#">Home</a>
                            </li>
                            <li class="nav-item links">
                                <a class="nav-link" href="#about-church">Church</a>
                            </li>
                            <li class="nav-item links">
                                <a class="nav-link" href="#about-pastor">Pastor</a>
                            </li>
                            <li class="nav-item links">
                                <a class="nav-link" href="#gallery">Gallery</a>
                            </li>
                            <li class="nav-item links">
                                <a class="nav-link" href="#contact">Contacts</a>
                            </li>

                           <li class="nav-item dropdown">
                                <?php if ($profileImagePath): ?>
                                    <!-- Profile Image Dropdown -->
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="<?= $profileImagePath ?>" alt="Profile" class="img-fluid rounded-circle" style="border: 1px solid green; width: 40px; height: 40px;">
                                </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                        <li><a class="dropdown-item" href="features/authentication/function/logout.php">Log Out</a></li>
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
        <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="assets/fellowship/fellowship.jpg" class="d-block w-100" alt="assets/fellowship/fellowship.jpg">
                <div class="carousel-caption">
                    <h5>First slide label</h5>
                    <p>Some representative placeholder content for the first slide.</p>
                </div>
                </div>
                <div class="carousel-item">
                <img src="assets/fellowship/fellowship.jpg" class="d-block w-100" alt="assets/fellowship/fellowship.jpg">
                <div class="carousel-caption">
                    <h5>Second slide label</h5>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
                </div>
                <div class="carousel-item">
                <img src="assets/fellowship/fellowship.jpg" class="d-block w-100" alt="assets/fellowship/fellowship.jpg">
                <div class="carousel-caption">
                    <h5>Third slide label</h5>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
    </section>
    <section class="about_church" id="about-church">
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 mt-4">
                    <h5 class="mb-2 fw-bold">About Church</h5>
                    <img src="assets/first-bg.PNG" alt="" class=" fluid w-100 rounded-3">
                </div>
                <div class="col-md-6 mt-4">
                    <h3 class="fw-bold mb-3 text-center mt-4">About Central Bible Baptist Church</h3>
                    <p class="mb-0 p-4 pgraph">Central Bible Baptist Church began in 1998 with a vision placed in the heart of <b>Pastor
                         Herbert Collano</b> a faithful servant burdened to start a mission. After receiving the blessing of his mentor, <b>Pastor Benny Abante Sr.</b> Pastor Herbert first set out to Bicol but was later led back to Valenzuela. Through a series of divine appointments, he was called to minister in Trece Martires, Cavite, where he started with only two Bible study members and no financial support—just strong faith and a heart to serve.</p>
                        <div class="d-flex justify-content-center justify-content-md-start">
                             <a href="features/users/web/about_church.php" class="more text-decoration-none">Read More </a>
                        </div>
                </div>
              
            </div>
        </div>
    </section>
   <section class="about_pastor" id="about-pastor">
    <div class="pastor_logo"></div>
    <div class="container mt-4 position-relative">
        <div class="row">
            <div class="col-md-6 mt-4">
                <h3 class="fw-bold mb-3 text-center mt-4">About Our Pastor</h3>
                <p class="mb-0 p-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum quisquam, atque rerum a quia error obcaecati, laudantium qui, illum eius ipsum? Suscipit ipsam, quas fuga quo repellendus dicta illo obcaecati.</p>
            </div>
            <div class="col-md-6 mt-4">
                <img src="assets/pastor.jpg" alt="" class="w-100 rounded-3">
            </div>
        </div>
    </div>
    </section>

    <section class="church_gallery mt-4 mb-4" id="gallery">
        <div class="container">
            <h3 class="fw-bold">Church Gallery</h3>
            <div class="row d-flex justify-content-center">
                <div class="col-md-5 mt-4">
                    <a href="features/users/web/baptism.php">
                        <img src="assets/baptism/baptism.PNG" class="w-100 rounded-1 h-100" alt="">
                    </a>
                </div>
                <div class="col-md-5 mt-4">
                    <a href="features/users/web/fellowship.php">
                        <img src="assets/fellowship/fellowship.jpg" class="w-100 rounded-1 h-100" alt="">
                    </a>
                </div>
                <div class="col-md-5 mt-4">
                    <a href="features/users/web/ministries.php">
                        <img src="assets/fellowship/fellowship.jpg" class="w-100 rounded-1 h-100" alt="">
                    </a>
                </div>
                  <div class="col-md-5 mt-4">
                    <a href="features/users/web/missions.php">
                        <img src="assets/fellowship/fellowship.jpg" class="w-100 rounded-1 h-100" alt="">
                    </a>
                </div>
                <div class="col-md-5 mt-4">
                    <a href="features/users/web/future_events.php">
                        <img src="assets/fellowship/fellowship.jpg" class="w-100 rounded-1 h-100" alt="">
                    </a>
                </div>
                <div class="col-md-5 mt-4">
                    <a href="features/users/web/past_events.php">
                        <img src="assets/fellowship/fellowship.jpg" class="w-100 rounded-1 h-100" alt="">
                    </a>
                </div>
            </div>
        </div>

    </section>
    <section class="contact text-black" id="contact">
    <div class="overlay"></div>
    <div class="container  py-5 contact-container">
        <div class="row align-items-center">
        <!-- Left Content -->
        <div class="col-lg-6 mb-4 text-white">
            <h1 class="fw-bold">Love to hear from you,<br>Let’s talk</h1>
            <p class="mt-3">We’d love to hear from you! Whether you have questions, need prayer, or want to learn more about our church, we’re here to help.</p>
            <p><i class="bi bi-envelope icon"></i> <strong>Email</strong><br>cbbctrece@gmail.com</p>
            <p><i class="bi bi-geo-alt icon"></i> <strong>Location</strong><br> 2118 Thornridge Cir. Syracuse</p>
        </div>

        <!-- Right Form -->
        <div class="col-lg-6">
            <div class="contact-box">
            <h5 class="fw-bold mb-4">Send us a message</h5>
            <form action="features/users/function/php/contact_form.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Your name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name">
                </div>
                <div class="mb-3">
                    <label for="facebook_name" class="form-label">Facebook Name</label>
                    <input type="text" class="form-control" id="facebook_name" name="facebook_name" placeholder="Enter your facebook name">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Write your message</label>
                    <textarea class="form-control" id="message" rows="4" name="message" placeholder="Let us know how we can assist you..."></textarea>
                </div>
                <button type="submit" class="btn btn-green w-100">Send Message</button>
            </form>

            </div>
        </div>
        </div>
    </div>
    </section>

</body>
</html>