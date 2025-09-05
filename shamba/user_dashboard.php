<?php
include 'db.php';
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$bookings_query = $conn->prepare("SELECT b.*, m.name as machinery_name, m.price_per_hour 
                                FROM bookings b 
                                JOIN machinery m ON b.machinery_id = m.id 
                                WHERE b.user_id = ?");
$bookings_query->bind_param("i", $user_id);
$bookings_query->execute();
$bookings = $bookings_query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            color: #1a1a1a;
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 2rem 0;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar h1 {
            color: white;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
        }

        .welcome-text {
            color: white;
            text-align: center;
            margin-bottom: 2.5rem;
            padding: 0 1.5rem;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar-nav a {
            color: #fff;
            text-decoration: none;
            padding: 1.2rem 2rem;
            transition: all 0.3s ease;
            display: block;
            font-size: 1.1rem;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(10px);
        }

        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
            font-weight: bold;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2.5rem;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 1.2rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #2a5298;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .actions a {
            color: #2a5298;
            text-decoration: none;
            margin-right: 1rem;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .actions a:hover {
            color: #1e3c72;
        }

        .book-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
            text-align: center;
            min-width: 120px;
        }

        .book-btn:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .rejection-reason {
            color: #dc3545;
            font-style: italic;
            font-size: 0.95em;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #fff5f5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h1>Shamba Palace</h1>
        <div class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <nav class="sidebar-nav">
            <a href="#" onclick="showSection('my-bookings')" class="active">My Bookings</a>
            <a href="#" onclick="showSection('book-machinery')">Book Machinery</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <div class="container">
            <section id="my-bookings" class="section">
                <h2>My Bookings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Machinery</th>
                            <th>Date</th>
                            <th>Hours</th>
                            <th>Status</th>
                            <th>Total Cost</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];
                            $bookings = $conn->query("SELECT b.*, m.name as machinery_name, m.price_per_hour 
                                                    FROM bookings b 
                                                    JOIN machinery m ON b.machinery_id = m.id 
                                                    WHERE b.user_id = $user_id");
                            while ($booking = $bookings->fetch_assoc()): 
                                $total_cost = $booking['hours'] * $booking['price_per_hour'];
                            ?>
                                <tr>
                                    <td><?php echo $booking['machinery_name']; ?></td>
                                    <td><?php echo $booking['date']; ?></td>
                                    <td><?php echo $booking['hours']; ?></td>
                                    <td><?php echo $booking['status']; ?></td>
                                    <td>$<?php echo number_format($total_cost, 2); ?></td>
                                    <td>
                                        <?php if($booking['status'] === 'rejected' && !empty($booking['rejection_reason'])): ?>
                                            <div class="rejection-reason">
                                                Reason: <?php echo htmlspecialchars($booking['rejection_reason']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile;
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section id="book-machinery" class="section" style="display: none;">
                <h2>Available Machinery</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price Per Hour</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $machinery = $conn->query("SELECT * FROM machinery WHERE status = 'available'");
                        while ($machine = $machinery->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($machine['name']); ?></td>
                                <td><?php echo htmlspecialchars($machine['type']); ?></td>
                                <td>$<?php echo number_format($machine['price_per_hour'], 2); ?></td>
                                <td><?php echo htmlspecialchars($machine['status']); ?></td>
                                <td>
                                    <a href="book_machinery.php?id=<?php echo urlencode($machine['id']); ?>" class="book-btn">Book Now</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <script>
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });
        
        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
        
        // Update active state in navigation
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.classList.remove('active');
            if(link.getAttribute('onclick').includes(sectionId)) {
                link.classList.add('active');
            }
        });
    }

    // Show book-machinery section if URL has the parameter
    if(window.location.hash === '#book-machinery') {
        showSection('book-machinery');
    }
    </script>
</body>
</html>
<?php $conn->close(); ?>
