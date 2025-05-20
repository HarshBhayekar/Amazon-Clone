<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    echo "<script>alert('Session expired. Please register again.'); window.location.href='register.html';</script>";
    exit;
}

$email = $_SESSION['email'];
$name = $_SESSION['name'];

// Generate a new 6-digit OTP
$new_otp = rand(100000, 999999);
$_SESSION['otp'] = $new_otp;

// Send OTP via email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'harshtamsa@gmail.com'; // Your Gmail
    $mail->Password = "cyeytrrprfhnawim"; // App password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('harshtamsa@gmail.com', 'Amazon Registration');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Amazon Registration - Resend OTP";
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 20px; border: 2px solid #ddd; border-radius: 10px; text-align: center;'>
        
        <img src='https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg' alt='Amazon' style='width: 150px; margin-bottom: 20px;'>

        <p style='font-size: 18px; font-weight: bold; color: #333;'>Resend OTP</p>

        <p>Hello <strong>$name</strong>,</p>
        <p>Your new OTP for verification is:</p>
        
        <div style='text-align: center; padding: 20px; font-size: 24px; font-weight: bold; background-color: #232F3E; color: #fff; border-radius: 5px; letter-spacing: 3px;'>
            $new_otp
        </div>

        <p style='font-size: 14px;'>This OTP is valid for <strong>10 minutes</strong>. Do not share this code.</p>
        
        <p>If you did not request this, please ignore this email.</p>

        <br>
        <p>Best Regards,</p>
        <p><strong>Amazon Team</strong></p>
        <hr style='border: 0; border-top: 1px solid #ddd;'>
        <p style='font-size: 12px; color: #777;'>This is an automated email, please do not reply.</p>
    </div>
    ";

    $mail->send();

    echo "<script>alert('A new OTP has been sent to your email.'); window.location.href='verifymail.html';</script>";
} catch (Exception $e) {
    echo "<script>alert('Error sending OTP: " . $mail->ErrorInfo . "'); window.location.href='verifymail.html';</script>";
}
?>
