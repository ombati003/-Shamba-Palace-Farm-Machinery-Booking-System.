<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            padding: 0;
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
            overflow-y: auto;
        }

        .sidebar h1 {
            color: white;
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 2rem;
            padding: 0 1.5rem;
        }

        .welcome-text {
            color: white;
            text-align: center;
            margin-bottom: 2.5rem;
            padding: 0 1.5rem;
            font-size: 1.1rem;
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
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(10px);
        }

        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2.5rem;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2.5rem;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .add-btn {
            background-color: #4CAF50;
            color: white;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: auto;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 1.25rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #2c3e50;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .actions {
            display: flex;
            gap: 1rem;
        }

        .actions a {
            color: #555;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .actions a:hover {
            color: #4CAF50;
        }

        #settings form {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.75rem;
            color: #444;
            font-weight: 600;
            font-size: 1.1rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h1>Shamba Palace</h1>
        <div class="welcome-text">Welcome, Admin</div>
        <nav class="sidebar-nav">
            <a href="#" onclick="showSection('users')" class="active">Manage Users</a>
            <a href="#" onclick="showSection('machinery')">Manage Machinery</a>
            <a href="#" onclick="showSection('bookings')">Manage Bookings</a>
            <a href="#" onclick="showSection('settings')">Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <div class="container">
            <section id="users" class="section">
                <h2>Users Management</h2>
                <a href="add_user.php" class="add-btn">Add New User</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = $conn->query("SELECT * FROM users");
                        while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td class="actions">
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section id="machinery" class="section" style="display: none;">
                <h2>Machinery Management</h2>
                <a href="add_machinery.php" class="add-btn">Add New Machinery</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price Per Hour</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $machinery = $conn->query("SELECT * FROM machinery");
                        while ($machine = $machinery->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $machine['id']; ?></td>
                                <td><?php echo $machine['name']; ?></td>
                                <td><?php echo $machine['type']; ?></td>
                                <td><?php echo $machine['price_per_hour']; ?></td>
                                <td><?php echo $machine['status']; ?></td>
                                <td class="actions">
                                    <a href="edit_machinery.php?id=<?php echo $machine['id']; ?>">Edit</a>
                                    <a href="delete_machinery.php?id=<?php echo $machine['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <section id="bookings" class="section" style="display: none;">
                <h2>Bookings Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Machinery</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Hours</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $bookings = $conn->query("SELECT b.*, m.name as machinery_name FROM bookings b JOIN machinery m ON b.machinery_id = m.id");
                        while ($booking = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo $booking['machinery_name']; ?></td>
                                <td><?php echo $booking['customer_name']; ?></td>
                                <td><?php echo $booking['date']; ?></td>
                                <td><?php echo $booking['hours']; ?></td>
                                <td><?php echo $booking['status']; ?></td>
                                <td class="actions">
                                    <?php if($booking['status'] == 'pending'): ?>
                                        <a href="approve_booking.php?id=<?php echo $booking['id']; ?>" class="approve-btn">Approve</a>
                                        <button onclick="showRejectDialog(<?php echo $booking['id']; ?>)" class="reject-btn">Reject</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Rejection Dialog -->
                <div id="rejectDialog" class="modal" style="display: none;">
                    <div class="modal-content">
                        <h3>Reject Booking</h3>
                        <form id="rejectForm">
                            <input type="hidden" id="bookingId" name="bookingId">
                            <div class="form-group">
                                <label for="rejectReason">Reason for Rejection:</label>
                                <textarea id="rejectReason" name="rejectReason" required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="confirm-btn">Confirm Rejection</button>
                                <button type="button" onclick="hideRejectDialog()" class="cancel-btn">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <style>
                    .modal {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.5);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 1000;
                    }
                    .modal-content {
                        background: white;
                        padding: 2rem;
                        border-radius: 12px;
                        width: 500px;
                        max-width: 90%;
                    }
                    .form-group {
                        margin-bottom: 1.5rem;
                    }
                    .form-group textarea {
                        width: 100%;
                        height: 120px;
                        padding: 1rem;
                        margin-top: 0.5rem;
                        border: 2px solid #ddd;
                        border-radius: 6px;
                        resize: vertical;
                        font-size: 1rem;
                    }
                    .form-actions {
                        display: flex;
                        gap: 1rem;
                        justify-content: flex-end;
                    }
                    .approve-btn, .reject-btn, .confirm-btn, .cancel-btn {
                        padding: 0.75rem 1.5rem;
                        border-radius: 6px;
                        cursor: pointer;
                        border: none;
                        font-size: 1rem;
                        font-weight: 500;
                    }
                    .approve-btn {
                        background: #4CAF50;
                        color: white;
                        text-decoration: none;
                    }
                    .reject-btn {
                        background: #f44336;
                        color: white;
                    }
                    .confirm-btn {
                        background: #f44336;
                        color: white;
                    }
                    .cancel-btn {
                        background: #777;
                        color: white;
                    }
                </style>

                <script>
                    function showRejectDialog(bookingId) {
                        document.getElementById('bookingId').value = bookingId;
                        document.getElementById('rejectDialog').style.display = 'flex';
                    }

                    function hideRejectDialog() {
                        document.getElementById('rejectDialog').style.display = 'none';
                        document.getElementById('rejectReason').value = '';
                    }

                    document.getElementById('rejectForm').addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const bookingId = document.getElementById('bookingId').value;
                        const reason = document.getElementById('rejectReason').value;
                        
                        try {
                            const response = await fetch('reject_booking.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    bookingId: bookingId,
                                    rejectReason: reason
                                })
                            });
                            
                            const result = await response.json();
                            if (result.success) {
                                hideRejectDialog();
                                location.reload();
                            } else {
                                alert('Error rejecting booking: ' + result.error);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error rejecting booking');
                        }
                    });
                </script>
            </section>

            <section id="settings" class="section" style="display: none;">
                <h2>Settings</h2>
                <form action="update_settings.php" method="post">
                    <div class="form-group">
                        <label>Site Title:</label>
                        <input type="text" name="site_title" value="Shamba Palace">
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Email:</label>
                        <input type="email" name="admin_email" value="admin@shambapalace.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Change Password:</label>
                        <input type="password" name="new_password">
                    </div>
                    
                    <input type="submit" value="Save Settings">
                </form>
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
        
        // Update active state of nav links
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.classList.remove('active');
            if(link.getAttribute('onclick').includes(sectionId)) {
                link.classList.add('active');
            }
        });
    }
    </script>
</body>
</html>
<?php $conn->close(); ?>
