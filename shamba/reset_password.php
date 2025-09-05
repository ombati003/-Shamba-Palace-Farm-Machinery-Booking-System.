<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match";
        exit();
    }

    // Check if user exists and email matches
    if ($user_type === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND email = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
    }

    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update password
        if ($user_type === 'admin') {
            $update_stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        }
        
        $update_stmt->bind_param("ss", $new_password, $username);
        
        if ($update_stmt->execute()) {
            echo "Password updated successfully! <a href='login.html'>Return to login</a>";
        } else {
            echo "Error updating password: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        echo "Invalid username or email";
    }

    $stmt->close();
    $conn->close();
}
?>
