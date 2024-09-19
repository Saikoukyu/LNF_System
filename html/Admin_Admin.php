<?php 
    include("../php/connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Lost & Found Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/admin_admin.css">
    
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
                <i class="fas fa-cogs"></i><span>IT Admin Setting</span>
            </li>
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
                <a href="lost and found.html" class="add-lost-found">
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

        <script>
            // Dropdown toggle script
            document
              .querySelector(".dropdown-caret")
              .addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default action
                this.closest(".dropdown").classList.toggle("open"); // Toggle the dropdown menu
              });
    
            document.addEventListener("click", function (event) {
              var isClickInside = document
                .querySelector(".dropdown")
                .contains(event.target);
    
              if (!isClickInside) {
                document.querySelector(".dropdown").classList.remove("open");
              }
            });
    
            const logoutButton = document.getElementById("logoutButton");
    
            logoutButton.addEventListener("click", function () {
              window.location.href = "NU_LoginPage.html";
            });
          </script>

        <section class="content">
            <h2><i class="fas fa-user"></i> Admin</h2>
            <hr class="header-line">
                <div class="menu-items">
                    <div class="menu-item">
                        <h3>REPORTS</h3>
                        <button class="edit-btn" onclick="navigateTo('REPORT EDITING.html')">REPORTS</button>
                    </div>
                    <div class="menu-item">
                        <h3>ITEMS</h3>
                        <button class="edit-btn" onclick="navigateTo('ITEMS EDITING.html')">ITEMS</button>
                    </div>
                    <div class="menu-item">
                        <h3>FORM</h3>
                        <button class="edit-btn" onclick="navigateTo('FORM EDITING.html')">FORM</button>
                    </div>
                </div>
    
        </section>
    </div>

    <script>
        function navigateTo(page) {
            window.location.href = page;
        }

        function editProfile() {
            window.location.href = 'EDIT PROFILE.html';
        }
		
    </script>

</body>
</html>
