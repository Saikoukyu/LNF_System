<?php
session_start(); // Start the session
$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lost & Found Management System</title>
    <link rel="stylesheet" href="/css/admin_report.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
            <span>MENU</span>
        </div>
        <div class="sidebar-greeting">
            Hello, <?php
            // Dynamically show the username or placeholder based on session (assumed username is stored in session)
            echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
            ?>
        </div>
        <ul>
            <li onclick="window.location.href='Admin_Dashboard.php'">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </li>
            <li onclick="window.location.href='item view.php'">
                <i class="fas fa-eye"></i><span>Item View</span>
            </li>
            <li onclick="window.location.href='Admin_Report.php'">
                <i class="fas fa-file-alt"></i><span>Report</span>
            </li>
            <li onclick="window.location.href='Admin_Admin.php'">
                <i class="fas fa-user"></i><span>Admin</span>
            </li>
            <?php if ($role == 'IT_Admin') : ?>
            <li onclick="window.location.href='Admin_ITAdmin.php'">
                <i class="fas fa-cogs"></i><span>IT Admin Setting</span>
            </li>
            <?php endif; ?>
        </ul>
    </div>
        
    </div>

    <div class="content">
        <div class="header">
            <span class="system-title">
                <span>LOST & FOUND</span>
                <span>Management System</span>
            </span>

            <div class="right-menu">
            <a href="Lost_and_Found.php" class="add-lost-found">
                    <span class="plus">+</span>
                    <span class="lost">Lost</span>
                    <span class="and">&</span>
                    <span class="found">Found</span>
                </a>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user"></i> Admin 1
                        <i class="fas fa-caret-down dropdown-caret"></i> 
                    </a>
                    <div class="dropdown-content">
                        <a href="#" id="logoutButton">Logout</a>
                    </div>                    
                </div>
            </div>
        </div>

        <div class="admin-report">
            <i class="fas fa-file-alt"></i> Report
        </div>
        <hr class="header-line">
    
        <div class="report-item">
            <div class="report-header">
                <h2>Item #2100 was reported lost</h2>
            </div>
            <div class="report-content">
                <div class="report-details">
                    <p><strong>Name of Item:</strong> Gucci Wallet</p>
                    <p><strong>Date Lost:</strong> August 12, 2024</p>
                    <p><strong>Item Description:</strong> TITE</p>
                </div>
                <div class="report-image">
                    <img src="assets/wallet.jpg" alt="Gucci Wallet">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 12, 2024, 2:18 PM</p>
            </div>
        </div>
        
        <div class="report-item">
            <div class="report-header">
                <h2>Item #2101 was reported found</h2>
            </div>
            <div class="report-content">
                <div class="report-details">
                    <p><strong>Name of Item:</strong> Silver Ring</p>
                    <p><strong>Date Found:</strong> August 25, 2024</p>
                    <p><strong>Item Description:</strong> NEIL ASPAG</p>
                </div>
                <div class="report-image">
                    <img src="wallet.jpg" alt="Silver Ring">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 25, 2024, 3:20 PM</p>
            </div>
        </div>
        
        <div class="report-item">
            <div class="report-header">
                <h2>Item #2102 was reported lost</h2>
            </div>
            <div class="report-content">
                <div class="report-details">
                    <p><strong>Name of Item:</strong> Blue Backpack</p>
                    <p><strong>Date Lost:</strong> August 30, 2024</p>
                    <p><strong>Item Description:</strong> BLUE JAB</p>
                </div>
                <div class="report-image">
                    <img src="assets/wallet.jpg" alt="Blue Backpack">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 30, 2024, 9:45 AM</p>
            </div>
        </div>
        
        <div class="report-item">
            <div class="report-header">
                <h2>Item #2103 was reported found</h2>
            </div>
            <div class="report-content">
                <div class="report-details">
                    <p><strong>Name of Item:</strong> Car Keys</p>
                    <p><strong>Date Found:</strong> September 1, 2024</p>
                    <p><strong>Item Description:</strong> TINA MORAN</p>
                </div>
                <div class="report-image">
                    <img src="assets/wallet.jpg" alt="Car Keys">
                </div>
            </div>
            <div class="report-timestamp">
                <p>September 1, 2024, 10:30 AM</p>
            </div>
        </div>

        <div class="report-item">
            <div class="report-header">
                <h2>Item #2101 was reported lost</h2>
            </div>
            <div class="report-content">
                <div class="report-details">
                    <p><strong>Name of Item:</strong> Silver Ring</p>
                    <p><strong>Date Found:</strong> August 25, 2024</p>
                    <p><strong>Item Description:</strong> NEIL ASPAG</p>
                </div>
                <div class="report-image">
                    <img src="assets/wallet.jpg" alt="Silver Ring">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 25, 2024, 3:20 PM</p>
            </div>
        </div>

        <div class="report-item">
            <div class="report-header">
                <h2>Item #2101 was reported found</h2>
            </div>
            <div class="report-content">
                <div class="report-details">
                    <p><strong>Name of Item:</strong> Silver Ring</p>
                    <p><strong>Date Found:</strong> August 25, 2024</p>
                    <p><strong>Item Description:</strong> NEIL ASPAG</p>
                </div>
                <div class="report-image">
                    <img src="assets/wallet.jpg" alt="Silver Ring">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 25, 2024, 3:20 PM</p>
            </div>
        </div>

    </div>

    <script src="report.js"></script>
</body>
</html>
