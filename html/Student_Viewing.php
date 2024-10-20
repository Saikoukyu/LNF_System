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
    <style>
        /* Add custom styles for larger filters and buttons */
        .filter-bar input[type="text"],
        .filter-bar select {
            padding: 12px; /* Increase padding for larger input fields */
            font-size: 16px; /* Increase font size for better readability */
            margin-right: 10px; /* Space between inputs */
            width: 200px; /* Set a fixed width for consistency */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
        }

        .filter-bar button {
            padding: 12px 20px; /* Increase padding for larger buttons */
            font-size: 16px; /* Increase font size for better readability */
            cursor: pointer; /* Change cursor to pointer on hover */
            margin-left: 5px; /* Space between buttons */
            background-color: #4CAF50; /* Set a background color */
            color: white; /* Set button text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s; /* Transition for hover effect */
        }

        .filter-bar button:hover {
            background-color: #45a049; /* Darker shade on hover */
        }
    </style>
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


    
    <!-- Sidebar -->
    <div class="sidebar" id="mySidebar">
        <a href="#" id="view-items-link" class="active">
            <i class="fas fa-eye"></i> View Items
        </a>
        <a href="#" id="rules-link">
            <i class="fas fa-info-circle"></i> Lost & Found Rules
        </a>
    </div>
    <div class="main-content">
    <div class="search-bar" id="searchbar">
        <input type="text" id="searchInput" placeholder="Search items...">
    </div>

    <div class="filter-bar" id="filterbar">
        <label for="typeFilter">Type:</label>
        <input type="text" id="typeFilter" placeholder="Item Type">

        <label for="locationFilter">Found Location:</label>
        <select id="locationFilter">
            <option value="">Select Location</option>
            <option value="Entrance">Entrance</option>
            <option value="Comfort Room">Comfort Room</option>
            <option value="401 - 435">401 - 435</option>
            <option value="Comfort Room Male">Comfort Room Male</option>
            <option value="Comfort Room Female">Comfort Room Female</option>
            <option value="Function Hall">Function Hall</option>
            <option value="Accounting">Accounting</option>
            <option value="Registrar">Registrar</option>
            <option value="Hallway">Hallway</option>
            <option value="501 - 535">501 - 535</option>
            <option value="Gym">Gym</option>
            <option value="ITSO">ITSO</option>
            <option value="SDAO">SDAO</option>
            <option value="2nd Floor">2nd Floor</option>
            <option value="3rd Floor">3rd Floor</option>
            <option value="4th Floor">4th Floor</option>
            <option value="5th Floor">5th Floor</option>
        </select>

        <label for="dateFilter">Lost Date:</label>
        <input type="text" id="dateFilter" placeholder="MM/DD/YYYY">

        <button id="filterButton">Filter</button>
        <button id="resetButton">Reset</button>
    </div>

    <table class="lost-found-table" id="lostfoundtable">
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
                // Prepare the SQL query
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

                // Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // Fetch all results
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Check if there are any records
                if ($rows && count($rows) > 0) {
                    // Loop through each row and display
                    foreach ($rows as $row) {
                        $itemId = $row['item_id'];
                        $fullName = $row['fn_firstname'] . ' ' . $row['fn_lastname'];
                        $itemType = $row['it_name'];
                        $itemName = $row['in_name'];
                        $locationName = $row['specific_location_name'];
                        $dateLost = $row['date_lost'];
                        $formattedDateLost = date("m/d/Y", strtotime($dateLost));
                        $statusName = $row['status_name'];

                        $inquirySql = "SELECT COUNT(*) AS inquiry_count 
                        FROM tbl_inquiry 
                        WHERE inquiry_item_id = ?";
         $inquiryStmt = $conn->prepare($inquirySql);
         $inquiryStmt->execute([$itemId]);
         $inquiryCount = $inquiryStmt->fetch(PDO::FETCH_ASSOC)['inquiry_count'];

                        echo "<tr>";
                        echo "<td>" . $itemName . "</td>";
                        echo "<td>" . $locationName . "</td>";
                        echo "<td>" . $formattedDateLost . "</td>";
                        echo "<td>" . $statusName . "</td>";
                        echo "<td>" . $inquiryCount . "</td>";
                        // Check if the status is not "Claimed" before displaying the Inquire button
                if ($statusName !== "Claimed") {
                    echo '<td><a href="Lost_and_Found_Student_Item.php?item_id=' . $itemId . '" class="btn-box">Inquire</a></td>';
                } else {
                    echo '<td><span class="claimed-text">Item has been Claimed</span></td>'; // Optional message when claimed
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='14'>No items found</td></tr>";
        }
        ?>
    </tbody>
</table>
 <!-- Lost & Found Rules Section -->
 <div id="rules" class="rules">
            <h2>Lost and Found </h2>
            <p>Lost and found items are handled by the Student Discipline Office (SDO). Members of the NU community are expected to be guided by the University's Code of Conduct to maintain personal integrity and respect for the property of others.</p>

            <h3>Definitions</h3>
            <ul>
                <li><strong>Lost Property:</strong> Any unattended, abandoned, misplaced, or forgotten item found on University premises.</li>
                <li><strong>University Premises:</strong> All buildings, grounds, and residences owned, leased, rented, or otherwise under the control of the university.</li>
                <li><strong>Lost and Found Property Logbook:</strong> Consists of the details and data of the lost item managed by the personnel of the SDO.</li>
            </ul>

            <h3>Surrendering and Reporting Lost Items</h3>
            <ul>
                <li>All property inside the University Premises should be handed and surrendered to the SDO.</li>
                <li>The person who surrendered the item shall fill out the Lost and Found Property Logbook for security purposes.</li>
            </ul>

            <h3>Returning Lost Property to Owners</h3>
            <ul>
                <li>To claim a lost property by the SDO, owners must retrieve the item personally.</li>
                <li>In claiming the item, the owner must provide proof of ownership or a description of the lost property and its contents, if applicable.</li>
                <li>The owner must acknowledge receipt of the property by signing and dating the Lost and Found Property Logbook.</li>
            </ul>

            <h3>Disposing of Unclaimed Lost Property</h3>
            <ul>
                <li>Perishable and personal items that can emit foul odors must be claimed within 48 hours to prevent pest infestation.</li>
                <li>Non-perishable items can be claimed at the end of the term.</li>
            </ul>

            <h4>Items that are perishable and other personal items that can emit foul odor:</h4>
            <ul>
                <li>Food</li>
                <li>Beverages (Bottled drinks, juice, soft drinks, etc.)</li>
                <li>Personal Care items (toiletries, etc.)</li>
                <li>Hygiene Products (Deodorants or Body Sprays)</li>
                <li>Lunch Containers (with leftovers)</li>
                <li>Fabric (clothes, towel, jacket, socks, etc.)</li>
 
            </ul>

            <h4>Items that are non-perishable:</h4>
            <ul>
                <li>Accessories</li>
                <li>Electronics</li>
                <li>Books</li>
                <li>Bags</li>
                <li>Stationeries and other school supplies</li>
                <li>Keys and keychains</li>
                <li>Umbrella</li>
                <li>Shoes</li>
                <li>Hats, bullcap, etc.</li>
            </ul>

            <h3>Disposal Procedures</h3>
            <ul>
                <li>Unclaimed property that easily decay, release odor, or are perishable disposed of within 48 hours. 
				    Proper documentation (i.e., picture) will be provided.</li>
                <li>Unclaimed non-perishable property will be disposed of after the end of the academic year.</li>
                <li>Any items shredded or disposed of must be recorded in the Lost and Found Property Logbook.</li>
            </ul>
        </div>
    </div>


    <script>
                 // Function to hide and show sections
                 function toggleSections(isViewItems) {
            document.getElementById('rules').style.display = isViewItems ? 'none' : 'block'; // Show rules if not view items
            document.getElementById('lostfoundtable').style.display = isViewItems ? 'table' : 'none'; // Show table if view items
            document.getElementById('searchbar').style.display = isViewItems ? 'block' : 'none'; // Show search bar if view items
            document.getElementById('filterbar').style.display = isViewItems ? 'block' : 'none'; // Show filter bar if view items
        }

        // Sidebar active state handling
        document.getElementById('view-items-link').addEventListener('click', function() {
            toggleSections(true); // Show items, hide rules
            document.getElementById('view-items-link').classList.add('active');
            document.getElementById('rules-link').classList.remove('active');
        });

        document.getElementById('rules-link').addEventListener('click', function() {
            toggleSections(false); // Show rules, hide items
            document.getElementById('rules-link').classList.add('active');
            document.getElementById('view-items-link').classList.remove('active');
        });

    </script>

    <script>
        
         document.getElementById('inquiryButton').addEventListener('click', function() {
            window.location.href = '../html/Lost_and_Found_Student.php';
        });




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

        // Filter functionality
        document.getElementById('filterButton').addEventListener('click', function() {
            const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
            const locationFilter = document.getElementById('locationFilter').value.toLowerCase();
            const dateFilter = document.getElementById('dateFilter').value; // MM/DD/YYYY
            const rows = document.getElementById('table-body').getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                let typeMatch = false;
                let locationMatch = false;
                let dateMatch = false;

                const cells = rows[i].getElementsByTagName('td');
                if (cells.length > 0) {
                    // Check Type filter
                    const typeText = cells[0].textContent || cells[0].innerText;
                    typeMatch = typeFilter === "" || typeText.toLowerCase().indexOf(typeFilter) > -1;

                    // Check Location filter
                    const locationText = cells[1].textContent || cells[1].innerText;
                    locationMatch = locationFilter === "" || locationText.toLowerCase() === locationFilter;

                    // Check Date filter (MM/DD/YYYY)
                    if (dateFilter) {
                        const rowDate = cells[2].textContent.trim();
                        dateMatch = rowDate === dateFilter; // Compare directly
                    } else {
                        dateMatch = true; // No date filter applied
                    }
                }

                // If all conditions match, display the row
                rows[i].style.display = (typeMatch && locationMatch && dateMatch) ? '' : 'none';
            }
        });

        // Reset functionality
        document.getElementById('resetButton').addEventListener('click', function() {
            document.getElementById('typeFilter').value = '';
            document.getElementById('locationFilter').selectedIndex = 0; // Reset dropdown
            document.getElementById('dateFilter').value = '';
            const rows = document.getElementById('table-body').getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                rows[i].style.display = ''; // Show all rows
            }
        });



    </script>

    <script> 
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
    
</script>

<script>

    const logoutButton = document.getElementById('logoutButton');
    logoutButton.addEventListener('click', function() {
        // Ensure the redirect URL is correct
        window.location.href = 'NU_LoginPage.php';
    });

    
</script>
</body>

</html>
