<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        // Use plain text password
        $new_password = $_POST['new_password'];
        
        // Update using the default admin email if none provided
        $admin_email = isset($_POST['admin_email']) && !empty($_POST['admin_email']) 
            ? $_POST['admin_email'] 
            : 'admin@shambapalace.com';

        $pwd_stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
        $pwd_stmt->bind_param("ss", $new_password, $admin_email);
        
        if ($pwd_stmt->execute()) {
            if ($pwd_stmt->affected_rows > 0) {
                header("Location: admin_dashboard.php?success=1");
                exit();
            } else {
                echo "No admin account found with email: " . $admin_email;
            }
        } else {
            echo "Error updating password: " . $pwd_stmt->error;
        }
        $pwd_stmt->close();
    } else {
        header("Location: admin_dashboard.php");
    }
    
    $conn->close();
}
?>


