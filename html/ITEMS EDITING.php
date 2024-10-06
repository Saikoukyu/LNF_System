<?php 
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
                        <th>Owner</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="btn-container">
            <button class="save-btn" id="saveBtn">Save</button>
            <button class="close-btn" id="closeBtn">Close</button>
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

            // Search/filter functionality
            document.getElementById('searchInput').addEventListener('input', function () {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#itemTable tbody tr');

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const text = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });

            // Save button functionality
            const saveBtn = document.getElementById('saveBtn');
            saveBtn.addEventListener('click', function() {
                alert('Save Successfully!');
            });

            // Close button functionality
            const closeBtn = document.getElementById('closeBtn');
            closeBtn.addEventListener('click', function() {
                window.location.href = 'Admin_Admin.php'; // Redirects back to Admin_Admin.html
            });
        });
    </script>
</body>
</html>
