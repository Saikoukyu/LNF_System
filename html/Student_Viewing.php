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
            </tr>
        </thead>
        <tbody id="table-body">
            <tr>
                <td contenteditable="true">Wallet</td>
                <td contenteditable="true">Library</td>
                <td contenteditable="true">2024-09-01</td>
                <td contenteditable="true">Lost</td>
            </tr>
            <tr>
                <td contenteditable="true">Phone</td>
                <td contenteditable="true">Cafeteria</td>
                <td contenteditable="true">2024-08-25</td>
                <td contenteditable="true">Found</td>
            </tr>
            <tr>
                <td contenteditable="true">Keys</td>
                <td contenteditable="true">Gym</td>
                <td contenteditable="true">2024-09-03</td>
                <td contenteditable="true">Lost</td>
            </tr>
            <tr>
                <td contenteditable="true">Bag</td>
                <td contenteditable="true">Hallway</td>
                <td contenteditable="true">2024-09-02</td>
                <td contenteditable="true">Lost</td>
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
        window.location.href = 'lost and found.html';
    });

        const logoutButton = document.getElementById('logoutButton');
        logoutButton.addEventListener('click', function() {
            window.location.href = 'NU_LoginPage.html';
        });
    </script>
</body>
</html>