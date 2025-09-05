<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM machinery WHERE id = $id");
    $machinery = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price_per_hour = $_POST['price_per_hour'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE machinery SET name = ?, type = ?, price_per_hour = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $type, $price_per_hour, $status, $id);

    if ($stmt->execute()) {
        echo "Machinery updated successfully!";
        header("Location: admin_dashboard.php");  
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Machinery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Edit Machinery</h1>
    <form action="edit_machinery.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $machinery['id']; ?>">

        <label for="name">Machinery Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $machinery['name']; ?>" required><br>

        <label for="type">Type:</label>
        <input type="text" id="type" name="type" value="<?php echo $machinery['type']; ?>" required><br>

        <label for="price_per_hour">Price Per Hour:</label>
        <input type="number" step="0.01" id="price_per_hour" name="price_per_hour" value="<?php echo $machinery['price_per_hour']; ?>" required><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Available" <?php if ($machinery['status'] == 'Available') echo 'selected'; ?>>Available</option>
            <option value="Not Available" <?php if ($machinery['status'] == 'Not Available') echo 'selected'; ?>>Not Available</option>
        </select><br>

        <button type="submit">Update Machinery</button>
    </form>
</body>
</html>
