<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST["otp"]);

    if (!isset($_SESSION['otp']) || $entered_otp != $_SESSION['otp']) {
        echo "<script>alert('Invalid OTP. Please try again!'); window.location.href='verifymail.html';</script>";
        exit;
    }

    // Retrieve user data from session
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $password = password_hash($_SESSION['password'], PASSWORD_DEFAULT); // Hash password

    // Insert verified user into database
    $sql = "INSERT INTO userdetails (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Unset session data after storing in DB
        session_unset();
        session_destroy();

        echo "<script>alert('Registration Successful! You can now log in.'); window.location.href='signin.html';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
