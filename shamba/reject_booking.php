<?php
include 'db.php';
session_start();

// Get JSON data from POST request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    // Get POST data from JSON
    $bookingId = $data['bookingId'];
    $reason = $data['rejectReason'];

    // Update booking status and add rejection reason
    $stmt = $conn->prepare("UPDATE bookings SET status = 'rejected', rejection_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request or data']);
}
?>
