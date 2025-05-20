<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include "connection.php"; // Include database connection

session_start(); // Start session to store OTP temporarily

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Validate email (only Gmail accounts allowed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, "@gmail.com")) {
        echo "<script>alert('Invalid Gmail address. Please enter a valid Gmail account.'); window.location.href='forgot_password.html';</script>";
        exit;
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT email FROM userdetails WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Email not found in the database
        echo "<script>alert('Email not registered. Please sign up first.'); window.location.href='Registration.html';</script>";
        exit;
    }

    $stmt->close();

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP in session
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Send OTP via email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'harshtamsa@gmail.com'; // Your Gmail ID
        $mail->Password = 'cyeytrrprfhnawim'; // App password (DO NOT USE YOUR REAL PASSWORD)
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('harshtamsa@gmail.com', 'Amazon OTP Verification');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Amazon OTP Verification for Password Reset";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 20px; border: 2px solid #ddd; border-radius: 10px; text-align: center;'>
                <img src='https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg' alt='Amazon' style='width: 150px; margin-bottom: 20px;'>

                <p style='font-size: 18px; font-weight: bold; color: #333;'>Email Verification for Password Reset</p>

                <p>Use the OTP below to verify your email:</p>
                
                <div style='text-align: center; padding: 20px; font-size: 24px; font-weight: bold; background-color: #232F3E; color: #fff; border-radius: 5px; letter-spacing: 3px;'>
                    $otp
                </div>

                <p style='font-size: 14px;'>This OTP is valid for <strong>10 minutes</strong>. Please do not share this code with anyone.</p>

                <p>Best Regards,</p>
                <p><strong>Amazon Team</strong></p>
            </div>
        ";

        $mail->send();

        // Redirect to OTP verification page
        header("Location: verify_otp_forgot_password.html");
        exit;

    } catch (Exception $e) {
        echo "<script>alert('Error sending OTP: " . $mail->ErrorInfo . "'); window.location.href='forgot_email_input.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='forgot_email_input.html';</script>";
}
?>
