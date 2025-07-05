


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBBC| Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_login.css">
</head>

<?php
    session_start();
    include 'db.php';

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            if ($role === 'admin') {
                header("Location: features/admin/web/admin.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Incorrect Credentials";
        }
    } else {
        $error = "Incorrect Credentials";
    }

    $stmt->close();
    $conn->close();
}
?>
<body>
    <div class="container m-1">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="row login-container">
                    <div class="col-md-5 login-left text-center">
                        <img src="bg.png" alt="Logo">
                    </div>
                    <div class="col-md-7 login-right">
                        <?php if (!empty($error)): ?>
                                <div class="alert alert-danger text-center"><?= $error ?></div>
                            <?php endif; ?>
                        <h5 class="mb-3">Log in</h5>
                        <form method="POST" action="">
                             
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox   " class="form-check-input" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-10 fw-bold form-button">Log in</button>
                            <div class="text-center mt-3">
                                <a href="signup.php">Don't have an account? <span class="sign-up">Sign Up</span></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>


</html>
