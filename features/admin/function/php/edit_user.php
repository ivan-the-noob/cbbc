<?php
session_start();

require_once '../../../../db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])) {

    $id = (int) $_POST['id'];
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: edit_user.php?id=$id");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format.";
        header("Location: edit_user.php?id=$id");
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User details updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating user. Please try again.";
    }

    header("Location: ../../web/admin.php");
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: ../../web/admin.ph");
    exit;
}
?>
