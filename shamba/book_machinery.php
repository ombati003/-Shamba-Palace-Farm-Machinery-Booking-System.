<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machinery_id = $_POST['machinery_id'];
    $date = $_POST['date'];
    $hours = $_POST['hours'];
    $user_id = $_SESSION['user_id'];
    $customer_name = $_SESSION['username'];
    
    $stmt = $conn->prepare("INSERT INTO bookings (machinery_id, user_id, customer_name, date, hours, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iissi", $machinery_id, $user_id, $customer_name, $date, $hours);

    if ($stmt->execute()) {
        header("Location: user_dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Get all available machinery
$stmt = $conn->prepare("SELECT * FROM machinery WHERE status = 'available'");
$stmt->execute();
$result = $stmt->get_result();
$machines = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Machinery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .booking-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .machine-details {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="booking-form">
        <h2>Book Machinery</h2>

        <form method="POST" action="">
            <div class="form-group">
                <label for="machinery_id">Select Machinery:</label>
                <select id="machinery_id" name="machinery_id" required>
                    <option value="">Choose a machine...</option>
                    <?php foreach($machines as $machine): ?>
                        <option value="<?php echo $machine['id']; ?>">
                            <?php echo htmlspecialchars($machine['name']); ?> - 
                            <?php echo htmlspecialchars($machine['type']); ?> - 
                            $<?php echo number_format($machine['price_per_hour'], 2); ?>/hour
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="hours">Number of Hours:</label>
                <input type="number" id="hours" name="hours" required min="1" max="24">
            </div>

            <button type="submit" class="submit-btn">Book Now</button>
        </form>
    </div>
</body>
</html>
