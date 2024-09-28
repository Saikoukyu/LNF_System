<?php 
    include("../php/connect.php");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Item View - Lost & Found Management System</title>
    <link rel="stylesheet" href="/css/admin_itemview.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
      <div class="sidebar">
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
            <span>MENU</span>
        </div>
        <div class="sidebar-greeting">
            Hello, Admin 1
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
            <li onclick="window.location.href='Admin_ITAdmin.php'">
                <i class="fas fa-cogs"></i><span>IT Admin</span>
            </li>
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
                        <i class="fas fa-user"></i> Admin 1
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
                        <tr>
                            <td>1</td>
                            <td>My watch</td>
                            <td>Acce</td>
                            <td>Rolex</td>
                            <td><img src="assets/rolex.jpg" alt="Item Image"></td>
                            <td>30/8/24</td>
                            <td>31/8/24</td>
                            <td>Tondo</td>
                            <td></td>
							<td>Alice</td>
                            <td>Found</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>My watch</td>
                            <td>Acce</td>
                            <td>Rolex</td>
                            <td><img src="assets/rolex.jpg" alt="Item Image"></td>
                            <td>30/8/24</td>
                            <td>31/8/24</td>
                            <td>Tondo</td>
                            <td>Ela</td>
							<td></td>
                            <td>Claimed</td>
                        </tr>
						 <tr>
                          <tr>
                            <td>1</td>
                            <td>My watch</td>
                            <td>Acce</td>
                            <td>Rolex</td>
                            <td><img src="assets/rolex.jpg" alt="Item Image"></td>
                            <td>30/8/24</td>
                            <td>31/8/24</td>
                            <td>Tondo</td>
                            <td></td>
							<td>Alice</td>
                            <td>Found</td>
                        </tr>
						  <tr>
                            <td>1</td>
                            <td>My watch</td>
                            <td>Acce</td>
                            <td>Rolex</td>
                            <td><img src="assets/rolex.jpg" alt="Item Image"></td>
                            <td>30/8/24</td>
                            <td>31/8/24</td>
                            <td>Tondo</td>
                            <td>Ela</td>
							<td></td>
                            <td>Claimed</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script>
        // Script for handling dropdown
        document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
            dropdown.addEventListener('click', function (e) {
                e.preventDefault();
                this.parentElement.classList.toggle('open');
            });
        });
        // Script for sidebar menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('open');
        });
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
const logoutButton = document.getElementById('logoutButton');

    logoutButton.addEventListener('click', function() {
        window.location.href = 'NU_LoginPage.php';
    });
    </script>
</body>
</html>
