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
    <link rel="stylesheet" href="../css/about_church.css">
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
                                    <a class="nav-link" href="#">About Church</a>
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

        <div class="container church ">
            <div class="row">
                <div class="col-md-6">
                    <img src="" alt="">
                </div>
                <div class="col-md-6">
                    <p class="mb-0 p-3 pgraph" style="text-indent: 2em">Central Bible Baptist Church began in 1998 with a vision placed in the heart of Pastor Herbert Collano—a faithful servant burdened to start a mission. After receiving the blessing of his mentor, Pastor Benny Abante Sr., Pastor Herbert first set out to Bicol but was later led back to Valenzuela. Through a series of divine appointments, he was called to minister in Trece Martires, Cavite, where he started with only two Bible study members and no financial support—just strong faith and a heart to serve.</p>

                    <p class="mb-0 p-3 pgraph" style="text-indent: 2em">Together with his wife, Ma’am Cyrell Collano, they faced trials including financial struggles, health issues, and personal loss, but God’s grace sustained them. The church grew steadily, moving from small gathering places in San Agustin and Maligaya, until it was officially organized in 2003 as Central Bible Baptist Church.</p>

                     <p class="mb-0 p-3 pgraph" style="text-indent: 2em">In 2013, the church was blessed to purchase land and build its own sanctuary in P12, where it continues to grow in number and spiritual strength. Today, over 22 years later, CBBC remains a thriving ministry dedicated to preaching the Gospel and abounding in the work of the Lord—faithful to its calling and mission.</p>
                </div>
                
            </div>
        </div>

        <div class="container map">
                <div class="col-md-12">
                     <div id="map"></div>
                </div>
               </div> 
        </div>
       <!-- <img src="../../../assets/fellowship/fellowship.jpg" class="fellow-bg img-fluid" alt=""> -->
    </section>

       <script>
        function initMap() {
            const location = { lat: 14.27722100, lng: 120.87275090 };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 18,
                center: location,
                mapTypeId: 'hybrid'
                
            });

            new google.maps.Marker({
                position: location,
                map: map,
                title: "7VGF+V4J, Trece Martires City, Cavite",
            });

            const CustomLabel = function(position, text, map) {
                this.position = position;

                const div = document.createElement("div");
                div.className = "custom-label";
                div.innerText = text;

                this.div = div;

                const panes = this.setMap(map);
            };

            CustomLabel.prototype = new google.maps.OverlayView();

            CustomLabel.prototype.onAdd = function () {
                const pane = this.getPanes().overlayImage;
                pane.appendChild(this.div);
            };

            CustomLabel.prototype.draw = function () {
                const point = this.getProjection().fromLatLngToDivPixel(this.position);
                if (point) {
                    this.div.style.position = "absolute";
                    this.div.style.left = point.x - this.div.offsetWidth / 2 + "px";
                    this.div.style.top = point.y - 40 + "px"; // position above marker
                }
            };

            CustomLabel.prototype.onRemove = function () {
                if (this.div.parentNode) {
                    this.div.parentNode.removeChild(this.div);
                }
            };

            new CustomLabel(location, "Central Bible Baptist Church", map);
        }
    </script>

   <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmgygVeipMUsrtGeZPZ9UzXRmcVdheIqw&libraries=places&callback=initMap"
    async
    defer>
    </script>

</body>
</html>

