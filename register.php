<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include "connection.php";

session_start(); // Start session to store user data temporarily

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Store user details & OTP in session
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $_SESSION['otp'] = $otp;

    // Send OTP via email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'harshtamsa@gmail.com';
        $mail->Password = "cyeytrrprfhnawim";
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('harshtamsa@gmail.com', 'Amazon Registration');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Amazon Registration - Email Verification";
        $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 20px; border: 2px solid #ddd; border-radius: 10px; text-align: center;'>
        
        <!-- Amazon Logo (Using a publicly accessible image) -->
        <img src='https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg' alt='Amazon' style='width: 150px; margin-bottom: 20px;'>

        <p style='font-size: 18px; font-weight: bold; color: #333;'>Email Verification</p>

        <p>Hello <strong>$name</strong>,</p>
        <p>Thank you for registering with Amazon! To complete your registration, please use the OTP below:</p>
        
        <!-- OTP Section -->
        <div style='text-align: center; padding: 20px; font-size: 24px; font-weight: bold; background-color: #232F3E; color: #fff; border-radius: 5px; letter-spacing: 3px;'>
            $otp
        </div>

        <p style='font-size: 14px;'>This OTP is valid for <strong>10 minutes</strong>. Please do not share this code with anyone.</p>
        
        <p>If you did not request this, please ignore this email.</p>

        <br>
        <p>Best Regards,</p>
        <p><strong>Amazon Team</strong></p>
        <hr style='border: 0; border-top: 1px solid #ddd;'>
        <p style='font-size: 12px; color: #777;'>This is an automated email, please do not reply.</p>
    </div>
";



        $mail->send();

        // Redirect to OTP verification page
        header("Location: verifymail.html");
        exit;

    } catch (Exception $e) {
        echo "Error sending OTP: " . $mail->ErrorInfo;
    }
}
?>