<?php 
    include("../php/connect.php");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Item View - Lost & Found Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            height: 100vh;
            overflow: hidden;
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

        .table-container {
            margin-top: 20px;
            width: 125%;
            margin-left: 5%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #35408e;
            color: white;
        }

        td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-bar {
            margin-bottom: 20px;
            margin-left: 105%;
            margin-top: 5%;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-container {
            margin-top: 10px;
            margin-left: 110%;
            display: flex;
            gap: 10px;
        }

        .save-btn, .close-btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .save-btn {
            background-color: #35408e;
            color: white;
        }

        .save-btn:hover {
            background-color: #2c3475;
        }

        .close-btn {
            background-color: #cfa92c;
            color: white;
        }

        .close-btn:hover {
            background-color: #f2c200;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .header {
                left: 200px;
                width: calc(100% - 200px);
            }
            .search-bar input[type="text"] {
                width: 100%;
            }
        }
    </style>
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
                    <tr>
                        <td>2</td>
                        <td>Wallet</td>
                        <td>Acc</td>
                        <td>Gucci</td>
                        <td><img src="assets/wallet.jpg" alt="Item Image"></td>
                        <td>1/9/24</td>
                        <td>2/9/24</td>
                        <td>Manila</td>
                        <td>Ella</td>
						<td></td>
                        <td>
                            <select>
                                <option value="found">Found</option>
                                <option value="lost">Lost</option>
                                <option value="dispose">Dispose</option>
                                <option value="return">Return</option>
                                <option value="claimed">Claimed</option>
                                <option value="unclaimed">Unclaimed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
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
                                <option value="found">Found</option>
                                <option value="lost">Lost</option>
                                <option value="dispose">Dispose</option>
                                <option value="return">Return</option>
                                <option value="claimed">Claimed</option>
                                <option value="unclaimed">Unclaimed</option>
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
            window.location.href = 'Admin_Admin.html'; // Redirects back to Admin_Admin.html
        });

        const logoutButton = document.getElementById('logoutButton');
        logoutButton?.addEventListener('click', function() {
            window.location.href = 'NU_LoginPage.html';
        });
    </script>
</body>
</html>
