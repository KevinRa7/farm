<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['otp_verified'])) {
    die("Access denied!"); // Prevent direct access
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['pass'];
    $email = $_SESSION['email'];

    // Update password in users table
    $stmt = $conn->prepare("UPDATE details SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    
    if ($stmt->execute()) {
        $stmt->close(); // Close previous statement

        // Delete OTP from database after successful password reset
        $stmt = $conn->prepare("DELETE FROM password_reset WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close(); // Close statement

        echo "Password changed successfully!";
        session_destroy();
        header("Location: login.html");
        exit;
    } else {
        echo "Error updating password!";
    }
}
?>
