<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST["otp"]);

    if (!isset($_SESSION['otp']) || $entered_otp != $_SESSION['otp']) {
        echo "<script>alert('Invalid OTP. Please try again!'); window.location.href='verify_otp_forgot_password.html';</script>";
        exit;
    }

    // OTP verification successful
    echo "<script>alert('OTP Verified Successfully!'); window.location.href='confirmpass.html';</script>";

    // Unset OTP session after successful verification
    unset($_SESSION['otp']);
}
?>
