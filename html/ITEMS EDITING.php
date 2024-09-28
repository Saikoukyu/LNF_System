<?php 
    include("../php/connect.php");
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
                        <th>Found Date</th>
                        <th>Found Location</th>
                        <th>Owner</th>
						<th>Finder</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                        <td>2</td>
                        <td>Wallet</td>
                        <td>Acc</td>
                        <td>Gucci</td>
                        <td><img src="assets/wallet.jpg" alt="Item Image"></td>
                        <td>1/9/24</td>
                        <td>2/9/24</td>
                        <td>Manila</td>
                        <td></td>
						<td>Alice</td>
                        <td>
                            <select>
                                <option value="claimed">Claimed</option>
                                <option value="unclaimed">Unclaimed</option>
                                <option value="disposed">Disposed</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Save and Close buttons added here -->
        <div class="btn-container">
            <button class="save-btn" id="saveBtn">Save</button>
            <button class="close-btn" id="closeBtn">Close</button>
        </div>
    </section>
    <script>
        // Function to filter table rows based on search input
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

        const logoutButton = document.getElementById('logoutButton');
        logoutButton?.addEventListener('click', function() {
            window.location.href = 'NU_LoginPage.php';
        });
    </script>
</body>
</html>
