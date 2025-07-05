<?php
session_start();

require_once '../../../../db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {

    $id = (int) $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting user. Please try again.";
    }

    header("Location: ../../web/admin.php");
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: ../../web/admin.php");
    exit;
}
?>
