<?php
session_start();
include "connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values
    $new_password = trim($_POST["new-password"]);
    $confirm_password = trim($_POST["confirm-password"]);

    // Check if email exists in session (from OTP process)
    if (!isset($_SESSION['email'])) {
        echo "<script>alert('Session expired! Please request a new OTP.'); window.location.href='forgot_password.html';</script>";
        exit;
    }

    $email = $_SESSION['email']; // Get email from session

    // Validate password
    if (strlen($new_password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.'); window.location.href='confirmpass.html';</script>";
        exit;
    }

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again!'); window.location.href='confirmpass.html';</script>";
        exit;
    }

    // Hash new password for security
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in the database
    $stmt = $conn->prepare("UPDATE userdetails SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        // Unset session after password reset
        session_unset();
        session_destroy();

        echo "<script>alert('Password reset successful! Please sign in with your new password.'); window.location.href='signin.html';</script>";
    } else {
        echo "<script>alert('Error updating password. Please try again later.'); window.location.href='confirmpass.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href='forgot_password.html';</script>";
}
?>
