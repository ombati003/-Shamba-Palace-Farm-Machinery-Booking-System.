<?php
// Start session
session_start();

// Include database connection
include 'db.php';

// Initialize response array
$response = array(
    'status' => '',
    'message' => ''
);

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Basic validation
if ($password !== $confirm_password) {
    $response['status'] = 'error';
    $response['message'] = 'Passwords do not match';
    echo json_encode($response);
    exit();
}

// Check if username or email already exists
$check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $response['status'] = 'error';
    $response['message'] = 'Username or email already exists';
    echo json_encode($response);
    exit();
}

// Insert new user
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $response['status'] = 'success';
    $response['message'] = 'Registration successful! Redirecting to login...';
    header('Location: login.html');
    exit();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Registration failed! Try again later.' . $stmt->error;
    echo json_encode($response);
}

$stmt->close();
$conn->close();
?>
