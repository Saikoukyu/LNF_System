<?php
include("../php/connect2.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/Student_Viewing.css">
</head>

<body>
    <header class="header">
        <div class="title">
            <span class="highlight">LOST & FOUND</span> <span>Management System</span>
        </div>
        <div class="user">
            <a href="#" class="user-link">
                <i class="fas fa-user"></i> <span>Student</span> <i class="fas fa-caret-down"></i>
            </a>
            <div class="dropdown-menu" id="dropdownMenu">
                <button id="logoutButton" class="logout-button">Logout</button>
            </div>
        </div>
    </header>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search items...">
    </div>

    <table class="lost-found-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Found Location</th>
                <th>Lost Date</th>
                <th>Status</th>
                <th>Number of Inquiries</th>
                <th>Inquire</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <tr>
                <?php
                // Step 1: Prepare the SQL query
                $sql = "SELECT td.*, 
                                       fn.fn_firstname, fn.fn_lastname, 
                                       it.it_name, 
                                       iname.in_name,
                                       loc.location_name, 
                                       sloc.specific_location_name, 
                                       tdate.date_lost, tdate.time_lost ,
                                       stat.status_name
                                FROM tbl_item_description td
                                JOIN tbl_full_name fn ON td.item_full_name_id = fn.fn_id
                                JOIN tbl_item_type it ON td.item_type_id = it.it_id
                                JOIN tbl_item_name iname ON td.item_name_id = iname.in_id
                                JOIN tbl_location loc ON td.item_location_id = loc.location_id
                                JOIN tbl_specific_location sloc ON td.item_specific_location_id = sloc.specific_location_id
                                JOIN tbl_time_date tdate ON td.item_time_date_id = tdate.time_date_id
                                JOIN tbl_status stat ON td.item_status_id = stat.status_id";

                // Step 2: Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // Step 3: Fetch all results
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Step 4: Check if there are any records
                if (count($rows) > 0) {
                    // Step 5: Loop through each row and display
                    foreach ($rows as $row) {
                        $fullName = $row['fn_firstname'] . ' ' . $row['fn_lastname'];
                        $itemType = $row['it_name'];
                        $itemName = $row['in_name'];
                        $locationName = $row['location_name'];
                        $specificLocation = $row['specific_location_name'];
                        $dateLost = $row['date_lost'];
                        $formattedDateLost = date("m/d/Y", strtotime($dateLost));
                        $timeLost = $row['time_lost'];
                        $formattedTimeLost = date("h:i A", strtotime($timeLost));
                        $statusName = $row['status_name'];

                        echo "<tr>";
                        echo "<td>" . $itemName . "</td>";
                        echo "<td>" . $specificLocation . "</td>";
                        echo "<td>" . $formattedDateLost . "</td>";
                        echo "<td>" . $statusName . "</td>";
                        echo '<td>0</td>';
                        echo '<td><button type="button" class="btn btn-info">Inquire</button></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No items found</td></tr>";
                }
                ?>
            </tr>
        </tbody>
    </table>

    <div class="inquiry-container">
        <button class="inquiry-button" id="inquiryButton">INQUIRY</button>
    </div>

    <script>
        function saveTableData() {
            const tableData = [];
            const rows = document.getElementById('table-body').getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                const rowData = [];
                for (let j = 0; j < cells.length; j++) {
                    rowData.push(cells[j].textContent);
                }
                tableData.push(rowData);
            }

            localStorage.setItem('tableData', JSON.stringify(tableData));
        }

        function loadTableData() {
            const tableData = JSON.parse(localStorage.getItem('tableData'));

            if (!tableData) return;

            const rows = document.getElementById('table-body').getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    cells[j].textContent = tableData[i][j];
                }
            }
        }

        document.getElementById('table-body').addEventListener('input', function() {
            saveTableData();
        });

        document.getElementById('searchInput').addEventListener('keyup', function() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const tableBody = document.getElementById('table-body');
            const rows = tableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                let match = false;
                const cells = rows[i].getElementsByTagName('td');

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const cellText = cells[j].textContent || cells[j].innerText;
                        if (cellText.toLowerCase().indexOf(input) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }

            if (input === '') {
                for (let i = 0; i < rows.length; i++) {
                    rows[i].style.display = '';
                }
            }
        });

        window.onload = function() {
            loadTableData();
        }

        document.querySelector('.inquiry-button').addEventListener('click', function() {
            this.classList.add('clicked');
            setTimeout(() => this.classList.remove('clicked'), 200);
        });

        document.querySelector('.user-link').addEventListener('click', function(event) {
            event.preventDefault();
            this.classList.toggle('active');
            const dropdownMenu = document.getElementById('dropdownMenu');
            if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                dropdownMenu.style.display = 'block';
            } else {
                dropdownMenu.style.display = 'none';
            }
        });

        document.getElementById('inquiryButton').addEventListener('click', function() {
            window.location.href = '../html/Lost_and_Found_Student.php';
        });

        const logoutButton = document.getElementById('logoutButton');
        logoutButton.addEventListener('click', function() {
            window.location.href = 'NU_LoginPage.php';
        });
    </script>
</body>

</html>