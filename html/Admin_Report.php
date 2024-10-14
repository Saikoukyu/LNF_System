<?php
session_start(); // Start the session
$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';

include("../php/connect2.php");

try {
    // Step 2: Prepare the SQL query to fetch the latest 8 item reports
    $sql = "SELECT td.*, 
                   fn.fn_firstname, fn.fn_lastname, 
                   it.it_name, 
                   iname.in_name,
                   loc.location_name, 
                   sloc.specific_location_name, 
                   tdate.date_lost, tdate.time_lost,
                   stat.status_name
            FROM tbl_item_description td
            JOIN tbl_full_name fn ON td.item_full_name_id = fn.fn_id
            JOIN tbl_item_type it ON td.item_type_id = it.it_id
            JOIN tbl_item_name iname ON td.item_name_id = iname.in_id
            JOIN tbl_location loc ON td.item_location_id = loc.location_id
            JOIN tbl_specific_location sloc ON td.item_specific_location_id = sloc.specific_location_id
            JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
            JOIN tbl_status stat ON td.item_status_id = stat.status_id
            ORDER BY tdate.date_lost DESC, tdate.time_lost DESC 
            LIMIT 8";  // Fetch the latest 8 reports

    // Step 3: Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Step 4: Fetch all results
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        // If no items are found, handle the error
        echo "No items found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
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
                        <i class="fas fa-user"></i><?php
            // Dynamically show the username or placeholder based on session (assumed username is stored in session)
            echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
            ?>
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
    
         <!-- Loop through the latest 8 reports -->
         <?php foreach ($items as $index => $item) : ?>
            <div class="report-item">
                <div class="report-header">
                    <h2>Item #<?php echo htmlspecialchars($item['item_id']); ?> was reported lost</h2>
                </div>
                <div class="report-content">
                    <div class="report-details">
                        <p><strong>Name of Item:</strong> <?php echo htmlspecialchars($item['in_name']); ?></p>
                        <p><strong>Date Lost:</strong> <?php echo date("F j, Y", strtotime($item['date_lost'])); ?></p>
                        <p><strong>Item Description:</strong> <?php echo htmlspecialchars($item['item_brand']); ?></p>
                    </div>
                    <div class="report-image">
                        <img src="<?php echo !empty($item['item_photo']) ? '../assets/' . htmlspecialchars($item['item_photo']) : 'https://via.placeholder.com/150'; ?>" alt="<?php echo htmlspecialchars($item['in_name']); ?>">
                    </div>
                </div>
                <div class="report-timestamp">
                    <p><?php echo date("F j, Y, h:i A", strtotime($item['date_lost'] . ' ' . $item['time_lost'])); ?></p>
                </div>
        
            </div>
        <?php endforeach; ?>
    </div>
        

    </div>

    <script src="report.js"></script>
</body>
</html>
