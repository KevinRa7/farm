<?php
session_start();
include 'connection.php'; // Your database connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'C:/xampp/php/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/php/PHPMailer-master/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $_SESSION['email']=$email;
    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", time() + 300); // Expire in 5 minutes

    // Delete any existing OTP for this email (prevents duplicate entries)
    $stmt = $conn->prepare("DELETE FROM password_reset WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Insert new OTP into the database
    $stmt = $conn->prepare("INSERT INTO password_reset (email, otp, otp_expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $otp, $otp_expiry);
    
    if ($stmt->execute()) {
        // Send OTP via email
        // require 'PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'kevinrmakpr@gmail.com';
        $mail->Password = 'poue aetj prsy fins';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('kevinramrkpr@gmail.com', 'farmtradder');
        $mail->addAddress($email);
        $mail->Subject = "Password Reset OTP";
        $mail->Body = "Your OTP for password reset is: $otp. This OTP is valid for 5 minutes.";

        if ($mail->send()) {
            echo "OTP sent to your email!";
            $flag=1;
        } else {
            echo "Failed to send OTP.";
        }
        if($flag == 1)
        {
            header("Location: otpenter.html");
        }
    } else {
        echo "Error generating OTP!";
    }
}
?>
