<?php
session_start(); // Start the session

// Debugging message to check execution
echo "Checking session and cookies...<br>";

// Check if session exists
if (!isset($_SESSION['email'])) {
    // Check if cookies exist
    if (isset($_COOKIE['email']) || isset($_COOKIE['username']) || isset($_COOKIE['role'])) {
        // Expire the cookies by setting their expiration date to a past time
        setcookie("username", "", time() - 10, "/");
        setcookie("email", "", time() - 10, "/");
        setcookie("role", "", time() - 10, "/");

        // Destroy the session
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session

        // Debugging message before redirect
        echo "<script>
                alert('Your session has expired. You will be redirected to the login page.');
                window.location.href = 'NU_LoginPage.php'; // Redirect to login page
              </script>";
        exit();
    } else {
        // If no session and no cookies, just destroy the session (if any)
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        
        // Debugging message before redirect
        echo "<script>
                alert('No Session Found. You will be redirected to the login page.');
                window.location.href = 'NU_LoginPage.php'; // Redirect to login page
              </script>";
        exit();
    }
} else {
    echo "Session found: " . $_SESSION['email']; // Debugging message
}

// Optionally retrieve role from session or cookie (if it was restored from cookie in previous checks)
$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';

// Include your database connection
include("../php/connect2.php");

try {
    // Step 1: Fetch count for Items Lost (count of inquiry_id in tbl_inquiry)
    $sql_lost_items = "SELECT COUNT(inquiry_id) AS total_lost FROM tbl_inquiry";
    $stmt_lost_items = $conn->prepare($sql_lost_items);
    $stmt_lost_items->execute();
    $lost_items = $stmt_lost_items->fetch(PDO::FETCH_ASSOC)['total_lost'];

    // Step 2: Fetch count for Items Found (count of item_id in tbl_item_description)
    $sql_found_items = "SELECT COUNT(item_id) AS total_found FROM tbl_item_description";
    $stmt_found_items = $conn->prepare($sql_found_items);
    $stmt_found_items->execute();
    $found_items = $stmt_found_items->fetch(PDO::FETCH_ASSOC)['total_found'];

    // Step 3: Fetch count for Total Item Inquiries (count of inquiry_id in tbl_inquiry)
    $sql_total_inquiries = "SELECT COUNT(inquiry_id) AS total_inquiries FROM tbl_inquiry";
    $stmt_total_inquiries = $conn->prepare($sql_total_inquiries);
    $stmt_total_inquiries->execute();
    $total_inquiries = $stmt_total_inquiries->fetch(PDO::FETCH_ASSOC)['total_inquiries'];


    // Step 4: Fetch count for Successfully Returned (count of item_status_id = '2' in tbl_item_description)
    $sql_returned_items = "SELECT COUNT(item_id) AS total_returned FROM tbl_item_description WHERE item_status_id = 2";
    $stmt_returned_items = $conn->prepare($sql_returned_items);
    $stmt_returned_items->execute();
    $returned_items = $stmt_returned_items->fetch(PDO::FETCH_ASSOC)['total_returned'];

    // Step 5: Fetch count for Items Disposed/Recycled (count of item_status_id = '3' in tbl_item_description)
    $sql_disposed_items = "SELECT COUNT(item_id) AS total_disposed FROM tbl_item_description WHERE item_status_id = 3";
    $stmt_disposed_items = $conn->prepare($sql_disposed_items);
    $stmt_disposed_items->execute();
    $disposed_items = $stmt_disposed_items->fetch(PDO::FETCH_ASSOC)['total_disposed'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}


// Fetch weekly data for lost items based on date_lost from tbl_time_date
$sql_weekly_lost = "SELECT WEEK(tdate.date_lost) AS week_number, COUNT(td.item_time_date_id) AS lost_count
                    FROM tbl_item_description td
                    JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
                    WHERE tdate.date_lost IS NOT NULL
                    GROUP BY WEEK(tdate.date_lost)
                    ORDER BY WEEK(tdate.date_lost) ASC
                    LIMIT 4";
$stmt_weekly_lost = $conn->prepare($sql_weekly_lost);
$stmt_weekly_lost->execute();
$weekly_lost_data = $stmt_weekly_lost->fetchAll(PDO::FETCH_ASSOC);

// Fetch weekly data for found items based on status (1 = Unclaimed, 2 = Claimed)
$sql_weekly_found = "SELECT stat.status_name, WEEK(tdate.date_lost) AS week_number, COUNT(td.item_status_id) AS found_count
                     FROM tbl_item_description td
                     JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
                     JOIN tbl_status stat ON td.item_status_id = stat.status_id
                     WHERE stat.status_id IN (1, 2) -- 1 for Unclaimed, 2 for Claimed
                     GROUP BY stat.status_name, WEEK(tdate.date_lost)
                     ORDER BY WEEK(tdate.date_lost) ASC
                     LIMIT 4";
$stmt_weekly_found = $conn->prepare($sql_weekly_found);
$stmt_weekly_found->execute();
$weekly_found_data = $stmt_weekly_found->fetchAll(PDO::FETCH_ASSOC);

// Prepare the arrays to store the weekly data
$weeks_lost = [];
$counts_lost = [];

$weeks_found_unclaimed = [];
$counts_found_unclaimed = [];

$weeks_found_claimed = [];
$counts_found_claimed = [];

// Process lost items data
foreach ($weekly_lost_data as $lost) {
    $weeks_lost[] = 'Week ' . $lost['week_number']; // Week labels
    $counts_lost[] = $lost['lost_count']; // Lost item counts
}

// Process found items data
foreach ($weekly_found_data as $found) {
    if ($found['status_name'] == 'Unclaimed') {
        $weeks_found_unclaimed[] = 'Week ' . $found['week_number']; // Week labels for unclaimed
        $counts_found_unclaimed[] = $found['found_count']; // Unclaimed item counts
    } elseif ($found['status_name'] == 'Claimed') {
        $weeks_found_claimed[] = 'Week ' . $found['week_number']; // Week labels for claimed
        $counts_found_claimed[] = $found['found_count']; // Claimed item counts
    }
}



try {
    // Fetch the two most recently lost distinct items (existing code)
    $sql_recent_lost = "SELECT DISTINCT td.item_id, 
                               td.*, 
                               fn.fn_firstname, fn.fn_lastname, 
                               it.it_name, 
                               iname.in_name,
                               loc.location_name, 
                               sloc.specific_location_name, 
                               tdate.date_lost, tdate.time_lost,
                               stat.status_name,
                               td.owner_name,
                               td.item_photo -- Fetch the item photo
                        FROM tbl_item_description td
                        JOIN tbl_full_name fn ON td.item_full_name_id = fn.fn_id
                        JOIN tbl_item_type it ON td.item_type_id = it.it_id
                        JOIN tbl_item_name iname ON td.item_name_id = iname.in_id
                        JOIN tbl_location loc ON td.item_location_id = loc.location_id
                        JOIN tbl_specific_location sloc ON td.item_specific_location_id = sloc.specific_location_id
                        JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
                        JOIN tbl_status stat ON td.item_status_id = stat.status_id
                        WHERE td.item_status_id = 1 -- Unclaimed (lost items)
                        ORDER BY td.item_id DESC -- Order by highest item_id
                        LIMIT 1";

    $stmt_recent_lost = $conn->prepare($sql_recent_lost);
    $stmt_recent_lost->execute();
    $recent_lost_items = $stmt_recent_lost->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the two most recently found items where owner_name is not empty (new code)
    $sql_recent_found = "SELECT DISTINCT td.item_id, 
                                 td.*, 
                                 fn.fn_firstname, fn.fn_lastname, 
                                 it.it_name, 
                                 iname.in_name,
                                 loc.location_name, 
                                 sloc.specific_location_name, 
                                 stat.status_name,
                                 td.owner_name,
                                 td.item_photo -- Fetch the item photo
                          FROM tbl_item_description td
                          JOIN tbl_full_name fn ON td.item_full_name_id = fn.fn_id
                          JOIN tbl_item_type it ON td.item_type_id = it.it_id
                          JOIN tbl_item_name iname ON td.item_name_id = iname.in_id
                          JOIN tbl_location loc ON td.item_location_id = loc.location_id
                          JOIN tbl_specific_location sloc ON td.item_specific_location_id = sloc.specific_location_id
                          JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
                          JOIN tbl_status stat ON td.item_status_id = stat.status_id
                          WHERE td.item_status_id = 2 -- Found (claimed items)
                          AND td.owner_name IS NOT NULL 
                          AND td.owner_name != '' -- Check if owner name is not empty
                          ORDER BY td.item_id DESC -- Order by highest item_id (most recent)
                          LIMIT 1";

    $stmt_recent_found = $conn->prepare($sql_recent_found);
    $stmt_recent_found->execute();
    $recent_found_items = $stmt_recent_found->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lost & Found Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/css/admin_dashboard.css" />

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
                <i class="fas fa-file-alt"></i><span>Archive</span>
            </li>
            <li onclick="window.location.href='Admin_Admin.php'">
                <i class="fas fa-user"></i><span>Admin</span>
            </li>
            <?php if ($role == 'IT_Admin'): ?>
                <li onclick="window.location.href='Admin_ITAdmin.php'">
                    <i class="fas fa-cogs"></i><span>IT Admin Setting</span>
                </li>
            <?php endif; ?>
        </ul>
    </div>


    <!-- Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <span class="system-title">
                <span>LOST & FOUND</span>
                <span>Management System</span>
            </span>

            <div class="right-menu">
                <a href="Lost_and_Found_Admin.php" class="add-lost-found">
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

        <!-- Admin Dashboard Title with Icon -->
        <div class="admin-dashboard">
            <i class="fas fa-tachometer-alt"></i>
            <span>Admin Dashboard</span> <!-- Placeholder for dashboard title -->
        </div>
        <hr class="header-line">

        <!-- Dashboard Cards -->
        <div class="dashboard">

            <div class="card yellow">
                <h3><i class="fas fa-map-marker-alt"></i> <?php echo $found_items; ?></h3> <!-- Items Found -->
                <p>Items Found</p>
            </div>
            <div class="card blue">
                <h3><i class="fas fa-clipboard-list"></i> <?php echo $total_inquiries; ?></h3>
                <!-- Total Item Inquiries -->
                <p>Student Item Inquiries</p> <!-- Updated label -->
            </div>
            <div class="card green">
                <h3><i class="fas fa-check-circle"></i> <?php echo $returned_items; ?></h3>
                <!-- Successfully Returned -->
                <p>Successfully Returned</p>
            </div>
            <div class="card red">
                <h3><i class="fas fa-trash-alt"></i> <?php echo $disposed_items; ?></h3>
                <!-- Items Disposed/Recycled -->
                <p>Items Disposed/Recycled</p>
            </div>
        </div>

        <!-- Grid Container -->
        <div class="grid-container">
            <div class="grid-item">
                <h4>Graphs</h4>

                <h4>Lost Items <em>in the last month</em></h4>
                <canvas id="lostItemsChart"></canvas>
                <h4>Unclaimed <em>in the last month</em></h4>
                <canvas id="foundItemsChart"></canvas>
                <h4>Claimed <em>in the last month</em></h4>
                <canvas id="ClaimedItemsChart"></canvas>

            </div>



            <!-- Existing loop for Recently Lost Items -->
            <?php if (!empty($recent_lost_items)): ?>
                <div class="grid-item">
                    <h4>Recently Lost Items</h4>
                    <?php foreach ($recent_lost_items as $lost): ?>
                        <p>Name: <?php echo htmlspecialchars($lost['in_name']); ?></p>
                        <p>Item Type: <?php echo htmlspecialchars($lost['it_name']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($lost['specific_location_name']); ?></p>
                        <p>Owner: <?php echo !empty($lost['owner_name']) ? $lost['owner_name'] : 'Not Released'; ?></p>
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($lost['item_photo']); ?>"
                                alt="<?php echo htmlspecialchars($lost['in_name']); ?>">
                            <a href="item_desc.php?item_id=<?php echo htmlspecialchars($lost['item_id']); ?>"
                                class="show-button">Show</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No recently lost items found.</p>
            <?php endif; ?>

            <!-- New loop for Recently Found Items -->
            <?php if (!empty($recent_found_items)): ?>
                <div class="grid-item">
                    <h4>Recently Found Items</h4>
                    <?php foreach ($recent_found_items as $found): ?>
                        <p>Name: <?php echo htmlspecialchars($found['in_name']); ?></p>
                        <p>Item Type: <?php echo htmlspecialchars($found['it_name']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($found['specific_location_name']); ?></p>
                        <p>Owner: <?php echo htmlspecialchars($found['owner_name']); ?></p>
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($found['item_photo']); ?>"
                                alt="<?php echo htmlspecialchars($found['in_name']); ?>">
                            <a href="item_desc.php?item_id=<?php echo htmlspecialchars($found['item_id']); ?>"
                                class="show-button">Show</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No recently found items available.</p>
            <?php endif; ?>

        </div>




        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Pass PHP arrays to JavaScript for Lost Items
            var lostWeeks = <?php echo json_encode($weeks_lost); ?>;
            var lostCounts = <?php echo json_encode($counts_lost); ?>;

            // Pass PHP arrays to JavaScript for Found Items
            var foundWeeksUnclaimed = <?php echo json_encode($weeks_found_unclaimed); ?>;
            var foundCountsUnclaimed = <?php echo json_encode($counts_found_unclaimed); ?>;

            var foundWeeksClaimed = <?php echo json_encode($weeks_found_claimed); ?>;
            var foundCountsClaimed = <?php echo json_encode($counts_found_claimed); ?>;

            // Lost Items Chart
            var lostCtx = document.getElementById('lostItemsChart').getContext('2d');
            var lostItemsChart = new Chart(lostCtx, {
                type: 'line',
                data: {
                    labels: lostWeeks, // Dynamic week labels for lost items
                    datasets: [{
                        label: 'Lost Items',
                        data: lostCounts, // Dynamic lost item counts
                        borderColor: 'cyan',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Found Items Chart with both Unclaimed and Claimed status
            var foundCtx = document.getElementById('foundItemsChart').getContext('2d');
            var foundItemsChart = new Chart(foundCtx, {
                type: 'line',
                data: {
                    labels: foundWeeksUnclaimed.length > foundWeeksClaimed.length ? foundWeeksUnclaimed : foundWeeksClaimed, // Choose the larger dataset for labels
                    datasets: [{
                        label: 'Unclaimed Items',
                        data: foundCountsUnclaimed, // Dynamic unclaimed item counts
                        borderColor: 'red',
                        borderWidth: 2,
                        fill: false
                    },]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Found Items Chart with both Unclaimed and Claimed status
            var foundCtx = document.getElementById('ClaimedItemsChart').getContext('2d');
            var foundItemsChart = new Chart(foundCtx, {
                type: 'line',
                data: {
                    labels: foundWeeksUnclaimed.length > foundWeeksClaimed.length ? foundWeeksUnclaimed : foundWeeksClaimed, // Choose the larger dataset for labels
                    datasets: [{
                        label: 'Claimed Items',
                        data: foundCountsClaimed, // Dynamic claimed item counts
                        borderColor: 'green',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            // Dropdown toggle script
            document.querySelector('.dropdown-caret').addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default action
                this.closest('.dropdown').classList.toggle('open'); // Toggle the dropdown menu
            });

            document.addEventListener('click', function (event) {
                var isClickInside = document.querySelector('.dropdown').contains(event.target);

                if (!isClickInside) {
                    document.querySelector('.dropdown').classList.remove('open');
                }
            });

            const logoutButton = document.getElementById('logoutButton');

            logoutButton.addEventListener('click', function () {
                window.location.href = "../php/logout.php";
            });
        </script>

        </script>
</body>

</html>