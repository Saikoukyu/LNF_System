<?php 
    include("../php/connect.php");
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
            Hello, Admin 1 <!-- Placeholder for admin name -->
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

    <!-- Content -->
    <div class="content">
        <!-- Header -->
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
                        <i class="fas fa-user"></i> Admin 1 <!-- Placeholder for admin name -->
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
            <div class="card lime">
                <h3><i class="fas fa-users"></i> 53</h3> <!-- Placeholder for items lost -->
                <p>Items Lost</p>
                <a href="#">Show all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card yellow">
                <h3><i class="fas fa-map-marker-alt"></i> 105</h3> <!-- Placeholder for items found -->
                <p>Items Found</p>
                <a href="#">Show all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card blue">
                <h3><i class="fas fa-clipboard-list"></i> 215</h3> <!-- Placeholder for user reported list -->
                <p>Student Reported List</p>
                <a href="#">Show all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card green">
                <h3><i class="fas fa-check-circle"></i> 68</h3> <!-- Placeholder for successfully returned items -->
                <p>Successfully Returned</p>
                <a href="#">Show all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card red">
                <h3><i class="fas fa-trash-alt"></i> 43</h3> <!-- Placeholder for items disposed/recycled -->
                <p>Items Disposed/Recycled</p>
                <a href="#">Show all <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Grid Container -->
        <div class="grid-container">
            <div class="grid-item">
                <h4>Quick Search</h4>
                <p>Find items quickly by searching here...</p>
                <div class="search-bar">
                    <input type="text" placeholder="Eg. iPhone 10 pro max, etc.">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <h4>Lost Items <em>in the last month</em></h4>
                <canvas id="lostItemsChart"></canvas>
                <h4>Found Items <em>in the last month</em></h4>
                <canvas id="foundItemsChart"></canvas>
            </div>
            <div class="grid-item">
                <h4>Recently Lost Items</h4>
                <p>Name: EO Gold Eyeglass</p> <!-- Placeholder for item name -->
                <p>Item Type: Accessories</p> <!-- Placeholder for item type -->
                <p>Brand: EO-Executive Optical</p> <!-- Placeholder for brand -->
                <p>Owner: Unknown</p> <!-- Placeholder for owner -->
                <div class="image-container">
                    <img src="assets/eyeglass.jpg" alt="Eyeglass"> <!-- Placeholder for image -->
                    <a href="#" class="show-button">Show</a>
                </div>
            </div>
            <div class="grid-item">
                <h4>Recently Found Items</h4>
                <p>Name:Rolex</p> <!-- Placeholder for item name -->
                <p>Item Type: Accessories</p> <!-- Placeholder for item type -->
                <p>Brand: Luxatic Rolex</p> <!-- Placeholder for brand -->
                <p>Owner: Elizabeth</p> <!-- Placeholder for owner -->
                <img src="assets/rolex.jpg" alt="Rolex Watch"> <!-- Placeholder for image -->
                <a href="#" class="show-button">Show</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Placeholder data for Lost Items Chart
        var lostCtx = document.getElementById('lostItemsChart').getContext('2d');
        var lostItemsChart = new Chart(lostCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Placeholder labels
                datasets: [{
                    label: 'Lost Items',
                    data: [12, 19, 3, 5], // Placeholder data
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

        // Placeholder data for Found Items Chart
        var foundCtx = document.getElementById('foundItemsChart').getContext('2d');
        var foundItemsChart = new Chart(foundCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Placeholder labels
                datasets: [{
                    label: 'Found Items',
                    data: [8, 11, 5, 13], // Placeholder data
                    borderColor: 'red',
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
        document.querySelector('.dropdown-caret').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default action
            this.closest('.dropdown').classList.toggle('open'); // Toggle the dropdown menu
        });

        document.addEventListener('click', function(event) {
            var isClickInside = document.querySelector('.dropdown').contains(event.target);

            if (!isClickInside) {
                document.querySelector('.dropdown').classList.remove('open');
            }
        });

    const logoutButton = document.getElementById('logoutButton');

    logoutButton.addEventListener('click', function() {
        window.location.href = 'NU_LoginPage.php';
    });
</script>

    </script>
</body>
</html>