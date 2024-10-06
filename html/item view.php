<?php
include("../php/connect2.php");
session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    echo "Session not found, redirecting...";  // Debugging message
    header('Location: NU_LoginPage.php'); // Redirect to login if not logged in
    exit();
} else {
    echo "Session found: " . $_SESSION['email']; // Debugging message
}

$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';
?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin Item View - Lost & Found Management System</title>
    <link rel="stylesheet" href="/css/admin_itemview.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <i class="fas fa-user"></i> <?php
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

        <section class="content">
            <h2><i class="fas fa-eye"></i> Item View</h2>
            <hr class="header-line">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search items...">
            </div>
            <div class="table-container">
                <table id="itemTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Specfic Name</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Location Found</th>
                            <th>Specific Location Found</th>
                            <th>Date Found</th>
                            <th>Time Found</th>
                            <th>Additional Info</th>
                            <th>Status</th>
                            <th>Item Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <?php
try {
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
            JOIN tbl_status stat ON td.item_status_id = stat.status_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows && count($rows) > 0) {
        foreach ($rows as $row) {
            $itemId = $row['item_id']; // Use item_id to filter inquiries
            echo "<tr>";
            echo "<td>" . $row['item_id'] . "</td>";
            echo "<td><img src='../assets/" . $row['item_photo'] . "' alt='Item Image' width='50'></td>";
            echo "<td>" . $row['item_detailed_name'] . "</td>";
            echo "<td>" . $row['it_name'] . "</td>";
            echo "<td>" . $row['in_name'] . "</td>";
            echo "<td>" . $row['item_brand'] . "</td>";
            echo "<td>" . $row['location_name'] . "</td>";
            echo "<td>" . $row['specific_location_name'] . "</td>";
            echo "<td>" . date("m/d/Y", strtotime($row['date_lost'])) . "</td>";
            echo "<td>" . date("h:i A", strtotime($row['time_lost'])) . "</td>";
            echo "<td>" . $row['item_add_info'] . "</td>";
            echo "<td>" . $row['status_name'] . "</td>";
            // Pass the item_id in the URL
            echo '<td><a href="item_desc.php?item_id=' . $itemId . '" class="btn-box">Details</a></td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='14'>No items found</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script>
        // Script for handling dropdown
        document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('open');
            });
        });
        // Script for sidebar menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
        // Function to filter table rows based on search input
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#itemTable tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const text = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
        const logoutButton = document.getElementById('logoutButton');

        logoutButton.addEventListener('click', function() {
            window.location.href = "../php/logout.php";
        });
    </script>
</body>

</html>