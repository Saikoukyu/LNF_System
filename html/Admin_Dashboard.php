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
    <style>
 body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    height: 100vh;
    overflow: hidden;
}

.sidebar {
    background-color: #35408e;
    color: white;
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    transition: transform 0.3s ease;
}

.sidebar-greeting {
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
    padding: 10px 0;
    border-bottom: 1px solid #2f3b82;
}

.menu-toggle {
    display: flex;
    align-items: center;
    padding-left: 20px;
    cursor: pointer;
    padding-top: 20px;
    padding-bottom: 10px;
}

.menu-toggle i {
    font-size: 24px;
    margin-right: 10px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    padding: 15px 20px;
    cursor: pointer;
    border-bottom: 1px solid #2f3b82;
    display: flex;
    align-items: center;
}

.sidebar ul li i {
    margin-right: 40px;
    min-width: 24px;
    text-align: center;
}

.sidebar ul li span {
    flex-grow: 1;
    text-align: left;
}

.sidebar ul li:hover,
.sidebar ul li.active {
    background-color: #2f3b82;
}

.content {
    margin-top: 70px;
    margin-left: 275px;
    padding: 20px;
    overflow-y: auto;
    height: calc(100vh - 70px);
}

.header {
    background-color: #35408e;
    color: white;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: fixed;
    top: 0;
    left: 250px;
    width: calc(100% - 250px);
    z-index: 999;
    border-bottom: 2px solid #e1bd59;
    box-sizing: border-box;
}

.system-title {
    font-size: 30px;
    font-weight: bold;
}

.system-title span:first-child {
    color: #e1bd59;
}

.right-menu {
    display: flex;
    align-items: center;
}

.right-menu a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    position: relative;
}

.add-lost-found span {
    font-weight: bold;
}

.add-lost-found .plus {
    color: #ffd100;
}

.add-lost-found .lost {
    color: #ff4b4b;
}

.add-lost-found .and,
.add-lost-found .found {
    color: #6ccfff;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    cursor: pointer;
}

.dropdown-toggle i {
    margin-right: 8px;
    margin-left: 8px;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 120px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 5px;
}

.dropdown-content a {
    color: black;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown.open .dropdown-content {
    display: block;
}

.admin-dashboard {
    display: flex;
    align-items: center;
    font-size: 20px;
    margin-top: 30px;
    padding-left: 20px;
    color: #35408e;
}

.admin-dashboard i {
    margin-right: 8px;
    font-size: 24px;
}

.header-line {
    border: 0;
    height: 1px;
    background: #d1d1d1;
    margin-top: 5px;
}

.dashboard {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
    justify-content: space-between;
}

.card {
    background-color: white;
    padding: 25px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    flex: 1;
    min-width: 180px;
    max-width: 18%;
    text-align: center;
    transition: box-shadow 0.3s ease;
}

.card h3 {
    margin: 15px 0;
    font-size: 24px;
}

.card p {
    margin: 10px 0;
    font-weight: bold;
}

.card a {
    display: inline-block;
    margin-top: 15px;
    color: #35408e;
    text-decoration: none;
    font-weight: bold;
}

.card.lime {
    background-color: #c1ff72;
}

.card.green {
    background-color: #ccf2d3;
}

.card.yellow {
    background-color: #fdf3cc;
}

.card.blue {
    background-color: #cce4f2;
}

.card.red {
    background-color: #f2cccc;
}

.card.lime h3,
.card.lime p {
    color: #588223;
}

.card.green h3,
.card.green p {
    color: #28a745;
}

.card.yellow h3,
.card.yellow p {
    color: #e1bd59;
}

.card.blue h3,
.card.blue p {
    color: #17a2b8;
}

.card.red h3,
.card.red p {
    color: #dc3545;
}

.grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
    margin: 20px auto;
    max-width: 1200px;
}

.grid-item {
    background-color: white;
    padding: 20px 10px 30px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    border: 1px solid #000;
}

.grid-item h4 {
    margin-top: 15px;
    font-size: 18px;
}

.grid-item p {
    font-style: italic;
    margin-top: -5px;
}

.search-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.search-bar input {
    padding: 10px;
    width: 70%;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.search-bar button {
    padding: 10px;
    background-color: #35408e;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.grid-item canvas {
    width: 100%;
    height: 100px;
    margin-top: 10px;
    max-height: 100px;
}

.image-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 10px;
}

.grid-item img {
    width: 100%;
    max-width: 300px;
    height: 200px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

.show-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #35408e;
    color: #e1bd59;
    text-decoration: none;
    border-radius: 25px;
    font-size: 14px;
    font-weight: bold;
    margin-top: 10px;
}

.show-button:hover {
    background-color: #0056b3;
    text-decoration: none;
}

.grid-item p a {
    color: #6c63ff;
    text-decoration: none;
    font-weight: bold;
}

.grid-item p a:hover {
    text-decoration: underline;
}

/* Responsive adjustments */
@media screen and (max-width: 1024px) {
    .dashboard {
        justify-content: space-around;
    }

    .card {
        max-width: 30%;
    }

    .content {
        margin-left: 220px;
    }

    .header {
        left: 220px;
        width: calc(100% - 220px);
    }
}

@media screen and (max-width: 768px) {
    .dashboard {
        flex-direction: column;
        align-items: center;
    }

    .card {
        max-width: 80%;
    }

    .grid-container {
        grid-template-columns: 1fr;
    }

    .sidebar {
        width: 200px;
    }

    .content {
        margin-left: 200px;
    }

    .header {
        left: 200px;
        width: calc(100% - 200px);
    }
}

@media screen and (max-width: 480px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .sidebar ul {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .content {
        margin: 0;
        padding-top: 100px;
    }

    .header {
        position: relative;
        top: 0;
        left: 0;
        width: 100%;
    }

    .grid-container {
        grid-template-columns: 1fr;
    }

    .card {
        max-width: 100%;
    }
}

    </style>
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
            <li onclick="window.location.href='Admin_Dashboard.html'">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </li>
            <li onclick="window.location.href='item view.html'">
                <i class="fas fa-eye"></i><span>Item View</span>
            </li>
            <li onclick="window.location.href='Admin_Report.html'">
                <i class="fas fa-file-alt"></i><span>Report</span>
            </li>
            <li onclick="window.location.href='Admin_Admin.html'">
                <i class="fas fa-user"></i><span>Admin</span>
            </li>
            <li onclick="window.location.href='Admin_ITAdmin.html'">
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
                <a href="#" class="add-lost-found">
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
        window.location.href = 'NU_LoginPage.html';
    });
</script>

    </script>
</body>
</html>