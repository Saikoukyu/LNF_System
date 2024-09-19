<?php 
    include("../php/connect.php");
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
    <button class="close-button" onclick="window.location.href='Admin_Admin.html'">Close</button>

    <div class="content">
        <!-- Report Item 1 -->
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
            <div class="report-actions">
                <button onclick="deleteItem(this)">Delete</button>
            </div>
        </div>
        
        <!-- Report Item 2 -->
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
                    <img src="assets/ring.jpg" alt="Silver Ring">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 25, 2024, 3:20 PM</p>
            </div>
            <div class="report-actions">
                <button onclick="deleteItem(this)">Delete</button>
            </div>
        </div>
        
        <!-- Report Item 3 -->
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
                    <img src="assets/backpack.jpg" alt="Blue Backpack">
                </div>
            </div>
            <div class="report-timestamp">
                <p>August 30, 2024, 9:45 AM</p>
            </div>
            <div class="report-actions">
                <button onclick="deleteItem(this)">Delete</button>
            </div>
        </div>
        
        <!-- Report Item 4 -->
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
                    <img src="assets/keys.jpg" alt="Car Keys">
                </div>
            </div>
            <div class="report-timestamp">
                <p>September 1, 2024, 10:30 AM</p>
            </div>
            <div class="report-actions">
                <button onclick="deleteItem(this)">Delete</button>
            </div>
        </div>
    </div>

    <script>
        function deleteItem(button) {
            const item = button.closest('.report-item');
            item.remove(); // Remove the item from the DOM
        }
    </script>
</body>
</html>
