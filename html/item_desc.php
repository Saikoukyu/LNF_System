<?php

session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    echo "Session not found, redirecting...";  // Debugging message
    header('Location: NU_LoginPage.php'); // Redirect to login if not logged in
    exit();
} else {
    echo "Session found: " . $_SESSION['email']; // Debugging message
}

$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';

include("../php/connect2.php");

if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];

    try {
        // Step 2: Prepare the SQL query to fetch item details based on the item_id
        $sql = "SELECT td.*, 
        fn.fn_firstname, fn.fn_lastname, 
        it.it_name, 
        iname.in_name,
        loc.location_name, 
        sloc.specific_location_name, 
        tdate.date_lost, tdate.time_lost,
        stat.status_name,
        td.owner_name, td.owner_email, td.return_date
 FROM tbl_item_description td
 JOIN tbl_full_name fn ON td.item_full_name_id = fn.fn_id
 JOIN tbl_item_type it ON td.item_type_id = it.it_id
 JOIN tbl_item_name iname ON td.item_name_id = iname.in_id
 JOIN tbl_location loc ON td.item_location_id = loc.location_id
 JOIN tbl_specific_location sloc ON td.item_specific_location_id = sloc.specific_location_id
 JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
 JOIN tbl_status stat ON td.item_status_id = stat.status_id
 WHERE td.item_id = :item_id";

        // Step 3: Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->execute();

        // Step 4: Fetch the result
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // If the item is found, store the values
            $fullName = $item['fn_firstname'] . ' ' . $item['fn_lastname'];
            $itemType = $item['it_name'];
            $itemName = $item['in_name'];
            $itemBrand = $item['item_brand'];
            $statusName = $item['status_name'];
            $founderEmail = $item['item_founder_email'];
            $itemPhoto = !empty($item['item_photo']) ? '../assets/' . $item['item_photo'] : 'https://via.placeholder.com/150'; // Placeholder if no image
            $itemLocation = $item['location_name'];
            $itemSpecificLocation = $item['specific_location_name'];
            $dateLost = $item['date_lost'];
            $formattedDateLost = date("m/d/Y", strtotime($dateLost));
            $timeLost = $item['time_lost'];
            $formattedTimeLost = date("h:i A", strtotime($timeLost));
        } else {
            // If no item is found, handle the error
            echo "Item not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "No item selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Description</title>
    <link rel="stylesheet" href="/css/item_desc.css">
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
            <?php if ($role == 'IT_Admin'): ?>
                <li onclick="window.location.href='Admin_ITAdmin.php'">
                    <i class="fas fa-cogs"></i><span>IT Admin Setting</span>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <span class="system-title">LOST & FOUND Management System</span>
            <div class="right-menu">
                <a href="#" class="add-lost-found">
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

        <div class="content">
            <div class="content-wrapper">
                <div class="item-description">
                    <h2>Item Description</h2>
                    <div class="item-container">
                        <div class="item-photo">
                            <img src="<?php echo $itemPhoto; ?>" alt="Item Photo">
                        </div>
                        <div class="item-details">
                            <p><strong>Specific Name:</strong> <?php echo $item['item_detailed_name']; ?></p>
                            <p><strong>Status:</strong> <?php echo $statusName; ?></p>
                            <p><strong>Type:</strong> <?php echo $itemType; ?></p>
                            <p><strong>Name:</strong> <?php echo $itemName; ?></p>
                            <p><strong>Brand:</strong> <?php echo $itemBrand; ?></p>
                            <p><strong>Founder Name:</strong> <?php echo $fullName; ?></p>
                            <p><strong>Founder Email:</strong> <?php echo $founderEmail; ?></p>
                        </div>
                    </div>

                    <div class="location-time">
                        <h2>Location / Time</h2>
                        <p><strong>Location:</strong> <?php echo $itemLocation; ?></p>
                        <p><strong>Specific Location:</strong> <?php echo $itemSpecificLocation; ?></p>
                        <p><strong>Time:</strong> <?php echo $formattedTimeLost; ?></p>
                        <p><strong>Date:</strong> <?php echo $formattedDateLost; ?></p>
                        <div id="owner-info">
                        <p><strong>Owner Name:</strong> <span id="ownerName">
                                <?php echo !empty($item['owner_name']) ? $item['owner_name'] : 'Not Released'; ?></p>
                            <p><strong>Owner Email:</strong> <span id="ownerEmail">
                                <?php echo !empty($item['owner_email']) ? $item['owner_email'] : 'Not Released'; ?></p>
                            <p><strong>Return Date:</strong><span id="returnDate">
                                <?php echo !empty($item['return_date']) ? $item['return_date'] : 'Not Released'; ?>
                            </p>

                        </div>

                        <!-- Edit Button -->
                        <button type="button" id="editOwnerBtn">Edit Owner Information</button>

                        <!-- Save Button (hidden initially) -->
                        <button type="button" id="saveOwnerBtn" style="display:none;">Save Changes</button>

                    </div>
                    <script>
                        document.getElementById('editOwnerBtn').addEventListener('click', function () {
                            // Make the owner fields editable
                            document.getElementById('ownerName').contentEditable = true;
                            document.getElementById('ownerEmail').contentEditable = true;
                            document.getElementById('returnDate').contentEditable = true;

                            // Show the save button
                            document.getElementById('saveOwnerBtn').style.display = 'inline-block';
                            document.getElementById('editOwnerBtn').style.display = 'none';
                        });

                        document.getElementById('saveOwnerBtn').addEventListener('click', function () {
                            // Get the updated owner information
                            var ownerName = document.getElementById('ownerName').innerText;
                            var ownerEmail = document.getElementById('ownerEmail').innerText;
                            var returnDate = document.getElementById('returnDate').innerText;

                            var itemId = <?= json_encode($itemId); ?>;  // Pass the item ID as a JavaScript variable

                            // Send AJAX request to save the updated information
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "../php/update_owner_info.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    try {
                                        var response = JSON.parse(xhr.responseText);
                                        if (response.status === "success") {
                                            alert("Owner information updated successfully.");
                                        } else {
                                            alert("Error updating owner information: " + response.message);
                                        }
                                    } catch (e) {
                                        console.error("Error parsing JSON response:", e);
                                    }
                                }
                            };

                            xhr.send("item_id=" + itemId + "&owner_name=" + encodeURIComponent(ownerName) + "&owner_email=" + encodeURIComponent(ownerEmail) + "&return_date=" + encodeURIComponent(returnDate));

                            // Make the fields non-editable again
                            document.getElementById('ownerName').contentEditable = false;
                            document.getElementById('ownerEmail').contentEditable = false;
                            document.getElementById('returnDate').contentEditable = false;

                            // Hide the save button and show the edit button
                            document.getElementById('saveOwnerBtn').style.display = 'none';
                            document.getElementById('editOwnerBtn').style.display = 'inline-block';
                        });
                    </script>




                </div>




                <?php
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "DB_LAF";
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Check if item_id is provided
                if (isset($_GET['item_id'])) {
                    $itemId = intval($_GET['item_id']); // Sanitize the item_id
                
                    // SQL query to get the inquiries related to the specific item
                    $sql = "SELECT 
                fn.fn_firstname, fn.fn_lastname, ir.item_req_sender_email, i.inquiry_id
            FROM tbl_inquiry i
            JOIN tbl_item_request ir ON i.inquiry_request_id = ir.item_req_id
            JOIN tbl_full_name fn ON ir.item_req_full_name_id = fn.fn_id
            WHERE i.inquiry_item_id = ?"; // Match inquiry_item_id to the item_id
                
                    // Prepare the query
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $itemId); // Bind item_id to the query
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Start generating the table
                    if ($result->num_rows > 0) {
                        echo '<div class="inquiries">
                <h2>Inquiries</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Check Inquiry</th>
                        </tr>
                    </thead>
                    <tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                    <td>' . $row["fn_firstname"] . ' ' . $row["fn_lastname"] . '</td>
                    <td>' . $row["item_req_sender_email"] . '</td>';
                            // Update the link to pass the inquiry_id instead of the item_id
                            echo '<td><a href="Inquiry_Description.php?inquiry_id=' . $row["inquiry_id"] . '" class="btn-box">Details</a></td>';
                            echo '</tr>';
                        }

                        echo '    </tbody>
                </table>
              </div>';
                    } else {
                        echo "<p>No inquiries found for this item.</p>";
                    }

                    $stmt->close();
                } else {
                    echo "<p>No item ID provided.</p>";
                }

                $conn->close();
                ?>

            </div>
        </div>
    </div>



    <script src="item_desc.js"></script>
</body>

</html>