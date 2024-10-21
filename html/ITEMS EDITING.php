<?php 

session_start(); // Start the session

// Debugging message to check execution
echo "Checking session and cookies...<br>";

// Check if session exists
if (!isset($_SESSION['email'])) {
    // Check if cookies exist
    if (isset($_COOKIE['email']) || isset($_COOKIE['username']) || isset($_COOKIE['role'])) {
        // Expire the cookies by setting their expiration date to a past time
        setcookie("username", "", time() - 3600, "/");
        setcookie("email", "", time() - 3600, "/");
        setcookie("role", "", time() - 3600, "/");

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

    include("../php/connect.php");
    include("../php/connect2.php");

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
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Item View - Lost & Found Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/admin_itemediting.css">
</head>
<body>
<section class="content">
 
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search items...">
            </div>

            <div class="filters">
                <div>
                    <label for="locationFilter">Location:</label>
                    <select id="locationFilter">
                        <option value="">Select Location</option>
                        <option value="1st Floor">1st Floor</option>
                        <option value="4th Floor">4th Floor</option>
                        <option value="5th Floor">5th Floor</option>
                        <option value="Student Lounge">Student Lounge</option>
                    </select>
                </div>
                <div>
                    <label for="itemTypeFilter">Item Type:</label>
                    <select id="itemTypeFilter">
                        <option value="">Select Item Type</option>
                        <option value="Personal Belongings">Personal Belongings</option>
                        <option value="School Supplies">School Supplies</option>
                        <option value="Electronic Devices">Electronic Devices</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Sports Equipment">Sports Equipment</option>
                        <option value="Documents and IDs">Documents and IDs</option>
                        <option value="Miscellaneous">Miscellaneous</option>
                    </select>
                </div>
                <div>
    <label for="dateFilter">Date:</label>
    <input type="date" id="dateFilter" />
</div>

                <div>
                    <button id="filterButton">Filter</button>
                    <button id="resetButton">Reset</button>
                </div>
            </div>

        <div class="table-container">
            <table id="itemTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Image</th>
                        <th>Lost Date</th>
                        <th>Found Location</th>
                        <th>Founder Name</th>
                        <th>Status</th>
                        <th>Item Deletion</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $item['item_id'] ?></td>
                            <td><?= $item['in_name'] ?></td>
                            <td><?= $item['it_name'] ?></td>
                            <td><?= $item['item_brand'] ?></td>
                            <td><img src="<?= !empty($item['item_photo']) ? '../assets/' . $item['item_photo'] : 'https://via.placeholder.com/150' ?>" alt="Item Image"></td>
                            <td><?= date("m/d/Y", strtotime($item['date_lost'])) ?></td>
                            <td><?= $item['location_name'] ?></td>
                            <td><?= $item['fn_firstname'] . ' ' . $item['fn_lastname'] ?></td>
                            <td>
                            <select class="status-select" data-item-id="<?php echo $item['item_id']; ?>">
                                <option value="1" <?php if ($item['item_status_id'] == 1) echo 'selected'; ?>>Unclaimed</option>
                                <option value="2" <?php if ($item['item_status_id'] == 2) echo 'selected'; ?>>Claimed</option>
                                <option value="3" <?php if ($item['item_status_id'] == 3) echo 'selected'; ?>>Disposed</option>
                            </select>
                            </td>
                            <td><div class="report-actions" data-item-id="<?= htmlspecialchars($item['item_id']); ?>">
                <button id="deleteBtn" onclick="deleteItem(<?= $item['item_id']; ?>)">Delete</button>
                </div></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<div class="container2">

        <div class="pagination-container">
    <button id="prevBtn" disabled>Previous</button>
    <span id="pageIndicator">Page 1</span>
    <button id="nextBtn">Next</button>
        </div>
        <div class="btn-container">
            <button class="close-btn" id="closeBtn">Close</button>
        </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle status change
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const itemId = this.getAttribute('data-item-id'); // Get the item ID
                    const newStatus = this.value; // Get the selected status value

                    console.log("Updating item_id:", itemId, "with status_id:", newStatus);

                    // Send the status change to the server using AJAX (fetch API)
                    fetch('../php/update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `item_id=${itemId}&status_id=${newStatus}` // Send the item_id and status_id
                    })
                    .then(response => response.text()) // Handle the server response
                    .then(data => {
                        console.log('Server response:', data); // Log server response for debugging
                        if (data.includes('Status updated successfully')) {
                            alert('Status updated successfully!');
                        } else {
                            alert('Error updating status: ' + data);
                        }
                    })
                    .catch(error => {
                        alert('Error updating status: ' + error.message);
                        console.error('Error:', error); // Log any error
                    });
                });
            });


    

            // Close button functionality
            const closeBtn = document.getElementById('closeBtn');
            closeBtn.addEventListener('click', function() {
                window.location.href = 'Admin_Admin.php'; // Redirects back to Admin_Admin.html
            });
        });


        let currentPage = 1;
const rowsPerPage = 6;
const table = document.getElementById('itemTable');
const tableBody = document.getElementById('tableBody');
const totalRows = tableBody.getElementsByTagName('tr').length;
const totalPages = Math.ceil(totalRows / rowsPerPage);
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const pageIndicator = document.getElementById('pageIndicator');

// Function to display rows for the current page
function displayRows() {
    const rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < totalRows; i++) {
        if (i >= (currentPage - 1) * rowsPerPage && i < currentPage * rowsPerPage) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
    pageIndicator.textContent = `Page ${currentPage}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
}

// Next button event listener
nextBtn.addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        displayRows();
    }
});

// Previous button event listener
prevBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        displayRows();
    }
});

// Initial display
displayRows();



function deleteItem(itemId) {
    if (confirm('Are you sure you want to delete this item?')) {
        // Create a FormData object to hold the data
        const formData = new FormData();
        formData.append('item_id', itemId);

        // Send the delete request using fetch with the POST method
        fetch('../php/delete_item.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Show the server response (success or error message)

            // If the deletion was successful, remove the item from the div
            if (data.includes('Item deleted successfully')) {
                const itemDiv = document.querySelector(`div[data-item-id="${itemId}"]`);
                if (itemDiv) {
                    window.location.href = 'ITEMS EDITING.php'; 
                    itemDiv.remove(); // Remove the item div
                }
            }
        })
        .catch(error => {
            alert('Error deleting item: ' + error.message);
            console.error('Error:', error);
        });
    }
}


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

      // Helper function to convert yyyy-mm-dd to mm/dd/yyyy
function formatDateToMMDDYYYY(dateString) {
    const date = new Date(dateString);
    const month = ('0' + (date.getMonth() + 1)).slice(-2); // Get month and add leading zero
    const day = ('0' + date.getDate()).slice(-2); // Get day and add leading zero
    const year = date.getFullYear();
    return `${month}/${day}/${year}`; // Return mm/dd/yyyy
}

function filterTable() {
    const location = document.getElementById('locationFilter').value.toLowerCase();
    const itemType = document.getElementById('itemTypeFilter').value.toLowerCase();
    const status = document.getElementById('statusFilter').value; // Selected status
    const dateInput = document.getElementById('dateFilter').value; // Using the date picker input
    const timeInput = document.getElementById('timeFilter').value; // Time in HH:MM format

    // Convert the date input to mm/dd/yyyy format
    const formattedDateInput = dateInput ? formatDateToMMDDYYYY(dateInput) : '';

    const rows = document.querySelectorAll('#itemTable tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const rowLocation = cells[6].textContent.toLowerCase(); // Location Found
        const rowItemType = cells[2].textContent.toLowerCase(); // Item Type
        const rowStatus = cells[8].querySelector('select').value; // Get selected status from dropdown
        const rowDate = cells[5].textContent; // Date Lost/Found (formatted as mm/dd/yyyy)
        const rowTime = cells[5].textContent.split(' ')[1]; // Extract time from date-time (if available)

        // No need to format rowDate as it's already mm/dd/yyyy

        // Match conditions
        const matchesLocation = location ? rowLocation.includes(location) : true;
        const matchesItemType = itemType ? rowItemType.includes(itemType) : true;
        const matchesStatus = status ? rowStatus === status : true; // Compare selected status
        const matchesDate = formattedDateInput ? rowDate === formattedDateInput : true; // Compare the date in mm/dd/yyyy format
        const matchesTime = timeInput ? rowTime.includes(timeInput) : true; // Compare the time (optional)

        // Display the row only if all conditions match
        if (matchesLocation && matchesItemType && matchesStatus && matchesDate && matchesTime) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event listener for filter button
document.getElementById('filterButton').addEventListener('click', filterTable);

// Reset button functionality
document.getElementById('resetButton').addEventListener('click', function() {
    document.getElementById('locationFilter').value = '';
    document.getElementById('itemTypeFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('timeFilter').value = '';

    // Reset all rows to be visible
    const rows = document.querySelectorAll('#itemTable tbody tr');
    rows.forEach(row => row.style.display = '');

    // Optionally reset the search input
    document.getElementById('searchInput').value = '';
});






    </script>
</body>
</html>
