<?php
session_start();
include "connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Fetch hashed password from database
    $stmt = $conn->prepare("SELECT password FROM userdetails WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Store user email in session
            $_SESSION['user_email'] = $email;

            // Redirect to index.html after successful login
            header("Location: index.html");
            exit();
        } else {
            echo "<script>alert('Invalid password! Please try again.'); window.location.href='signin.html';</script>";
        }
    } else {
        echo "<script>alert('User not found! Please register first.'); window.location.href='Registration.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
