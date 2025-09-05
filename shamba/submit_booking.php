<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machinery_id = $_POST['machinery_id'];
    $customer_name = $_POST['customer_name'];
    $date = $_POST['date'];
    $hours = $_POST['hours'];

    $stmt = $conn->prepare("INSERT INTO bookings (machinery_id, customer_name, date, hours) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $machinery_id, $customer_name, $date, $hours);

    if ($stmt->execute()) {
        echo "Booking submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
