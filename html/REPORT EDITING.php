<?php

session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    echo "Session not found, redirecting...";  // Debugging message
    header('Location: NU_LoginPage.php'); // Redirect to login if not logged in
    exit();
}

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
    <link rel="stylesheet" href="/css/admin_reportediting.css">
  
</head>
<body>
    <button class="close-button" onclick="window.location.href='Admin_Admin.php'">Close</button>

    <div class="content">
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
                <div class="report-actions">
                    <button onclick="deleteItem(<?php echo $item['item_id']; ?>)">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
        
    </div>

    <script>
        function deleteItem(itemId) {
            if (confirm('Are you sure you want to delete this item?')) {
                // Implement the delete logic (e.g., an AJAX request to delete the item)
                window.location.href = 'delete_item.php?item_id=' + itemId;
            }
        }
    </script>
</body>
</html>
