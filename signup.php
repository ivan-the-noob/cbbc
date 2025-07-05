
<?php 
    require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBBC| Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_login.css">
</head>

<body>
    <div class="container m-1">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="row login-container">
                    <div class="col-md-5 login-left text-center">
                        <img src="bg.png" alt="Logo">
                    </div>
                    <div class="col-md-7 login-right">
                        <h5 class="mb-3">Sign Up</h5>
                        <form method="POST" action="features/authentication/function/signup.php">
                             <div class="mb-3">
                                <input type="text" name="first_name" class="form-control" placeholder="first name" required>
                            </div>
                             <div class="mb-3">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
  
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-10 fw-bold form-button">Sign Up</button>
                            <div class="text-center mt-3">
                                <a href="login.php">Have an account? <span class="sign-up">Sign In</span></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>


</html>
