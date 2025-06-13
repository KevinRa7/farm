<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        die("Session email not set! Please try again.");
    }

    $user_otp = $_POST['otp'];
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM password_reset WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_otp, $otp_expiry);
        $stmt->fetch();

        if (strtotime($otp_expiry) < time()) {
            echo "OTP expired!";
        } elseif ($db_otp == $user_otp) {
            $_SESSION['otp_verified'] = true;
            $_SESSION['email'] = $email;

            header("Location: reset_password.html");
            exit;
        } else {
            echo "Invalid OTP!";
        }
    } else {
        echo "OTP not found!";
    }

    $stmt->close();
}
?>
